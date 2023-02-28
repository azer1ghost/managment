<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\Order;
use App\Models\Organization;
use Illuminate\Http\Request;

class TransitController extends Controller
{

    public function index()
    {
        return view('pages.transit.profile')->with([
            'orders' => Order::where('user_id', auth()->id())->latest()->paginate(8)
        ]);
    }

    public function show()
    {

    }

    public function edit()
    {
        return view('pages.transit.edit');
    }

    public function service()
    {
        return view('pages.transit.index');
    }

    public function login()
    {
        return view('pages.transit.login');
    }

    public function payment(Order $order)
    {
        return view('pages.transit.payment')->with(['order' => $order]);
    }

    public function profile()
    {
        return view('pages.transit.profile');
    }
}
