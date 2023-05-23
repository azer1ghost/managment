<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\FinanceClient;
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
        return response()->json(['message' => 'Müşteri yaratıldı'], 200);

    }

    public function getClients()
    {
        $clients = FinanceClient::get();
        return response()->json($clients);
    }
}
