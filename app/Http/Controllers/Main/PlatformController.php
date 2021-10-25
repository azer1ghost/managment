<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Update;
use App\Models\Widget;
use App\Services\MobexReferralApi;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['welcome', 'downloadBat']]);
    }

    /**
     * @throws FileNotFoundException
     */
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
        return view('panel.pages.main.dashboard', [
            'widgets' => Widget::isActive()->oldest('order')->get()
        ]);
    }

    public function languageSelector()
    {
        return view('auth.lang-selector');
    }

    public function test()
    {
        return \view('timeline')
            ->with(['updates' => Update::with('updates')
                ->get()
                ->groupBy('datetime')
            ]);
    }
}