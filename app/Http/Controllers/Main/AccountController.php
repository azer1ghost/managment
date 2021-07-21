<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function account(): View
    {
        return view('panel.pages.main.account');
    }

    public function save(Request $request): array
    {
        return $request->all();
    }
}
