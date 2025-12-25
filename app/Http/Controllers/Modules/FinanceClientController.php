<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceClientRequest;
use Illuminate\Support\Facades\DB;
use App\Models\FinanceClient;
use Illuminate\Http\Request;
use App\Models\Invoice;

class FinanceClientController extends Controller
{
    public function index()
    {
        return view('pages.finance.index');
    }

    public function createFinanceClient(Request $request)
    {
        $data = $request->only([
            'name',
            'voen',
            'hn',
            'mh',
            'bank',
            'bvoen',
            'code',
            'swift',
            'orderer',
        ]);

        FinanceClient::create($data);

        return response()->json(['message' => 'Client Created'], 200);
    }

    public function createFinanceInvoice(Request $request)
    {
        $data = $request->only([
            'company',
            'client',
            'invoiceNo',
            'invoiceDate',
            'paymentType',
            'protocolDate',
            'contractNo',
            'contractDate',
            'invoiceNumbers',
        ]);

        $services = $request->get('services', []);

        // Validate services array
        if (empty($services) || !is_array($services)) {
            return response()->json(['error' => 'At least one service is required'], 400);
        }

        // Filter out invalid services
        $validServices = array_filter($services, function($service) {
            return isset($service['input1']) &&
                   isset($service['input3']) &&
                   isset($service['input4']) &&
                   is_numeric($service['input3']) &&
                   is_numeric($service['input4']) &&
                   $service['input3'] > 0 &&
                   $service['input4'] > 0;
        });

        if (empty($validServices)) {
            return response()->json(['error' => 'At least one valid service is required'], 400);
        }

        $data['services'] = json_encode(array_values($validServices)); // Re-index array
        $data['created_by'] = auth()->id(); // Set the creator

        // Create invoice instance to calculate total_amount
        $invoice = new Invoice($data);
        $data['total_amount'] = $invoice->calculateTotalAmount(); // Calculate and store total amount

        $createdInvoice = Invoice::create($data);

        return response()->json([
            'message' => $data['company'],
            'id' => $createdInvoice->id
        ], 200);
    }

    /**
     * Update existing finance invoice (used from invoice view "Yadda Saxla")
     *
     * Note: We bypass Eloquent updating hook to allow full invoice edits and
     * update the record directly via query builder.
     */
    public function updateFinanceInvoice(Request $request, Invoice $invoice)
    {
        // Check if invoice is signed - signed invoices cannot be edited
        if ($invoice->is_signed == 1) {
            return response()->json([
                'error' => 'İmzalanmış qaimələr dəyişdirilə bilməz. Dəyişiklik etmək üçün qaiməni kopyalayın.'
            ], 403);
        }

        $data = $request->only([
            'company',
            'client',
            'invoiceNo',
            'invoiceDate',
            'paymentType',
            'protocolDate',
            'contractNo',
            'contractDate',
            'invoiceNumbers',
        ]);

        $services = $request->get('services', []);

        // Validate services array
        if (empty($services) || !is_array($services)) {
            return response()->json(['error' => 'At least one service is required'], 400);
        }

        // Filter out invalid services
        $validServices = array_filter($services, function ($service) {
            return isset($service['input1']) &&
                isset($service['input3']) &&
                isset($service['input4']) &&
                is_numeric($service['input3']) &&
                is_numeric($service['input4']) &&
                $service['input3'] > 0 &&
                $service['input4'] > 0;
        });

        if (empty($validServices)) {
            return response()->json(['error' => 'At least one valid service is required'], 400);
        }

        $data['services'] = json_encode(array_values($validServices)); // Re-index array

        // Recalculate total_amount using the same logic as on creation
        $tempInvoice = new Invoice($data);
        $data['total_amount'] = $tempInvoice->calculateTotalAmount();

        // Update invoice directly in DB to bypass immutability updating hook (since we already checked is_signed)
        DB::table('invoices')
            ->where('id', $invoice->id)
            ->update(array_merge($data, ['updated_at' => now()]));

        return response()->json([
            'message' => 'Invoice updated successfully',
            'id' => $invoice->id,
        ], 200);
    }

    public function getClients()
    {
        $clients = FinanceClient::get();
        return response()->json($clients);
    }

    public function invoices()
    {
        return view('pages.finance.invoices')->with(['invoices' => Invoice::with(['creator', 'financeClients'])->get()]);
    }
    public function clients()
    {
        return view('pages.finance.clients')->with(['clients' => FinanceClient::get(['id', 'name', 'voen'])]);
    }

    public function financeInvoice(Invoice $invoice)
    {
        return view('pages.finance.invoice')->with(['data' => $invoice]);
    }

    public function editFinanceClient(FinanceClient $client)
    {
        return view('pages.finance.client')->with(['data' => $client]);
    }

    public function updateFinanceClient(FinanceClient $client, FinanceClientRequest $request)
    {
        $client->update($request->validated());

        return redirect()
            ->route('editFinanceClient', $client)
            ->withNotify('success', $client->getAttribute('name'));
    }

    public function deleteInvoice(Invoice $invoice)
    {
        // Check authorization - only creator can delete
        if (auth()->id() !== $invoice->created_by) {
            abort(403, 'You are not authorized to delete this invoice.');
        }

        // Soft delete
        $invoice->delete();

        return redirect()->route('invoices')->withNotify('success', 'Invoice moved to trash successfully.');
    }

    /**
     * Show trash (deleted invoices)
     */
    public function trash()
    {
        $deletedInvoices = Invoice::onlyTrashed()
            ->with(['creator', 'financeClients'])
            ->get();

        return view('pages.finance.trash', compact('deletedInvoices'));
    }

    /**
     * Restore a deleted invoice
     */
    public function restoreInvoice($id)
    {
        $invoice = Invoice::onlyTrashed()->findOrFail($id);

        // Check authorization - only creator can restore
        if (auth()->id() !== $invoice->created_by) {
            abort(403, 'You are not authorized to restore this invoice.');
        }

        $invoice->restore();

        return redirect()->route('invoices.trash')->withNotify('success', 'Invoice restored successfully.');
    }

    /**
     * Permanently delete an invoice
     */
    public function forceDeleteInvoice($id)
    {
        $invoice = Invoice::onlyTrashed()->findOrFail($id);

        // Check authorization - only creator can permanently delete
        if (auth()->id() !== $invoice->created_by) {
            abort(403, 'You are not authorized to permanently delete this invoice.');
        }

        $invoice->forceDelete();

        return redirect()->route('invoices.trash')->withNotify('success', 'Invoice permanently deleted.');
    }

    /**
     * Duplicate (clone) an invoice
     */
    public function duplicateInvoice(Invoice $invoice)
    {
        $newInvoice = $invoice->replicate();

        // Generate next invoice number based on original (e.g. 178B -> 179B, 1a -> 2a)
        $originalNo = $invoice->invoiceNo;
        if (preg_match('/^(\d+)(.*)$/', $originalNo, $matches)) {
            $number = (int)$matches[1] + 1;
            $suffix = $matches[2];
            $newInvoiceNo = $number . $suffix;
        } else {
            // Fallback: append -COPY if format is unexpected
            $newInvoiceNo = $originalNo . '-COPY';
        }

        $newInvoice->invoiceNo = $newInvoiceNo;

        $newInvoice->created_by = auth()->id();
        $newInvoice->is_signed = 0; // Reset signed status
        $newInvoice->total_amount = null; // Let model recalculate on create
        $newInvoice->created_at = now();
        $newInvoice->updated_at = now();
        $newInvoice->save();

        return redirect()->route('financeInvoice', $newInvoice->id)->withNotify('success', 'Invoice duplicated successfully. You can now edit it.');
    }
    public function deleteFinanceClient(FinanceClient $client)
    {
        if ($client->delete()) {
            return back();
        }
        return response()->setStatusCode('204');
    }

    public function signInvoice(Request $request)
    {
        Invoice::whereId($request->get('id'))->update(['is_signed' => $request->get('sign')]);

        return response()->setStatusCode('200');
    }
}
