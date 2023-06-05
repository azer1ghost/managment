<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\FinanceClient;
use App\Models\Invoice;
use Illuminate\Http\Request;

class FinanceClientController extends Controller
{    public function index()
    {
        return view('pages.finance.index');
    }

    public function createFinanceClient(Request $request)
    {
        $name = $request->get('name');
        $voen = $request->get('voen');
        $hh = $request->get('hh');
        $mh = $request->get('mh');
        $bank = $request->get('bank');
        $bvoen = $request->get('bvoen');
        $code = $request->get('code');
        $swift = $request->get('swift');
        $orderer = $request->get('orderer');

        $data = [
            'name' => $name,
            'voen' => $voen,
            'hn' => $hh,
            'mh' => $mh,
            'code' => $code,
            'bank' => $bank,
            'bvoen' => $bvoen,
            'swift' => $swift,
            'orderer' => $orderer,
        ];

        FinanceClient::create($data);
        return response()->json(['message' => 'Client Created'], 200);
    }

    public function createFinanceInvoice(Request $request)
    {

        $company = $request->get('company');
        $client = $request->get('client');
        $invoiceNo = $request->get('invoiceNo');
        $invoiceDate = $request->get('invoiceDate');
        $paymentType = $request->get('paymentType');
        $protocolDate = $request->get('protocolDate');
        $contractNo = $request->get('contractNo');
        $contractDate = $request->get('contractDate');
        $invoiceNumbers = $request->get('invoiceNumbers');
        $services = json_encode($request->get('services'));

        $data = [
            'company' => $company,
            'client' => $client,
            'invoiceNo' => $invoiceNo,
            'invoiceDate' => $invoiceDate,
            'paymentType' => $paymentType,
            'protocolDate' => $protocolDate,
            'contractNo' => $contractNo,
            'contractDate' => $contractDate,
            'invoiceNumbers' => $invoiceNumbers,
            'services' => $services,
        ];

        Invoice::create($data);
        return response()->json(['message' => $company], 200);
    }

    public function getClients()
    {
        $clients = FinanceClient::get();
        return response()->json($clients);
    }
    public function invoices()
    {
        return view('pages.finance.invoices')->with(['invoices' => Invoice::get()]);
    }
    public function financeInvoice(Invoice $invoice)
    {
        return view('pages.finance.invoice')->with(['data' => $invoice]);
    }

    public function deleteInvoice(Invoice $invoice)
    {
        if ($invoice->delete()) {
            return back();
        }
        return response()->setStatusCode('204');
    }
}
