<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function welcome()
    {
        return view('pages.signature.welcome');
    }

    public function register()
    {
        return view('pages.signature.register');
    }
}
