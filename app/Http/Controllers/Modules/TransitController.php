<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\Organization;
use Illuminate\Http\Request;

class TransitController extends Controller
{

    public function service()
    {
        return view('pages.transit.index');
    }

    public function login()
    {
        return view('pages.transit.login');
    }
    public function payment()
    {
        return view('pages.transit.payment');
    }
}
