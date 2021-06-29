<?php

namespace App\Http\Controllers\Platform\Main;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function welcome(): View
    {
        header("Refresh: 5; URL=". route('login'));
        return view('panel.pages.main.welcome');
    }

    public function dashboard(): View
    {
        return view('panel.pages.main.dashboard');
    }
}
