<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['welcome', 'downloadBat']]);
    }

    public function downloadBat()
    {
        return response(
                    Storage::disk('public')
                        ->get('host.bat')
                    )
                ->header('Content-Type', 'application/x-bat');
    }

    public function welcome(): View
    {
        header("Refresh: 3; URL=". route('login'));
        return view('panel.pages.main.welcome');
    }

    public function dashboard(): View
    {
        $inquiriesToday = Inquiry::IsReal()->whereDate('datetime',  Carbon::today())->get()->count();
        $inquiriesMonth = Inquiry::IsReal()->whereMonth('datetime', Carbon::today())->get()->count();

        return view('panel.pages.main.dashboard', [
            'inquiriesToday' => $inquiriesToday,
            'inquiriesMonth' => $inquiriesMonth
        ]);
    }

    public function cabinet(): View
    {
        return view('panel.pages.cabinet.index');
    }

}
