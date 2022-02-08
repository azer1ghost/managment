<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function selectCompany(): View
    {
        return view('pages.signature.select')->with([
            'companies' => Company::all()
        ]);
    }

    public function signature(Company $company)
    {
        return view('pages.signature.render', compact('company'));
    }
}
