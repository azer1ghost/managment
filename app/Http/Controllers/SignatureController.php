<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function welcome(): View
    {
        return view('pages.signature.welcome');
    }

    public function selectCompany(): View
    {
        //$company = $request->get('company');

        return view('pages.signature.companies');
    }

    public function register(): View
    {
        return view('pages.signature.register');
    }

    public function registerEmployer(Request $request): array
    {
        return $request->all();
    }
}
