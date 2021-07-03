<?php

namespace App\Http\Controllers\Platform\Main;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['welcome']]);
    }

    public function welcome(): View
    {
        header("Refresh: 5; URL=". route('login'));
        return view('panel.pages.main.welcome');
    }

    public function dashboard(): View
    {
        return view('panel.pages.main.dashboard');
    }

    public function customerServices(): View
    {
        return view('panel.pages.customer-services.index')->with([
            "companies" => Company::select(['id','name'])->pluck('name','id')->toArray()
        ]);
    }
}
