<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceClientRequest;
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
        
        // Generate clean invoice number (remove any existing -COPY suffix and create new number)
        $baseInvoiceNo = preg_replace('/-COPY.*$/', '', $invoice->invoiceNo);
        $datePrefix = now()->format('Ymd');
        
        // Find the next available invoice number for today
        $lastInvoice = Invoice::where('invoiceNo', 'like', $datePrefix . '%')
            ->orderBy('invoiceNo', 'desc')
            ->first();
        
        if ($lastInvoice && preg_match('/' . $datePrefix . '(\d+)/', $lastInvoice->invoiceNo, $matches)) {
            $sequence = (int)$matches[1] + 1;
        } else {
            $sequence = 1;
        }
        
        $newInvoice->invoiceNo = $datePrefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        $newInvoice->created_by = auth()->id();
        $newInvoice->is_signed = 0; // Reset signed status
        $newInvoice->total_amount = null; // Reset to recalculate on save
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
