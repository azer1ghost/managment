<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Widget;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['welcome', 'downloadBat', 'documentTemporaryUrl']]);
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

    public function firebase()
    {
        return response(file_get_contents(storage_path('app/firebase-messaging-sw.js')))
            ->withHeaders([
                'Content-Type' => 'text/javascript'
            ]);
    }

    public function storeFcmToken(Request $request)
    {
        auth()->user()->devices()->updateOrCreate(
            ['device_key' => $request->cookie('device_key')],
            [
                'device' => $request->userAgent(),
                'ip' => $request->ip(),
                'fcm_token' => $request->get('fcm_token'),
            ]
        );
        return response()->json('OK');
    }

    public function setLocation(Request $request)
    {
        $coordinates = $request->get('coordinates');
        $lat = $coordinates['latitude'];
        $lng = $coordinates['longitude'];

        auth()->user()->devices()->updateOrCreate(
            ['device_key' => $request->cookie('device_key')],
            [
                'device' => $request->userAgent(),
                'ip' => $request->ip(),
                'location' => json_encode(['lat' => $lat, 'lng' => $lng])
            ]
        );
        return session()->get('location');
    }

    public function welcome(): View
    {
        header("Refresh: 3; URL=". route('login'));
        return view('panel.pages.main.welcome');
    }

    public function deactivated()
    {
        if(!auth()->user()->isDisabled()){
            return redirect()->route('dashboard', [
                'widgets' => Widget::isActive()->oldest('order')->get()
            ]);
        }

        return view('panel.pages.main.deactivated');
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
        return 'testing area';
    }

    public function documentTemporaryUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'document', now()->addMinutes(30), ['document' => $document]
        );
    }
}