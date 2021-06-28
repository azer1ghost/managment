<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function welcome(): View
    {
        header("Refresh: 5; URL=". route('login'));
        return view('pages.main.welcome');
    }

    public function register(): View
    {
        return view('pages.main.register');
    }

    public function registerEmployer(Request $request): array
    {
        return $request->all();
    }

    public function selectCompany(): View
    {
        return view('pages.main.companies')->with([
            'companies' => Company::all()
        ]);
    }



//    public function signature()
//    {
//        return view('');
//    }
}
