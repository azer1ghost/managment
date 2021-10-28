<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Update;
use App\Models\User;
use App\Models\Widget;
use App\Notifications\PushNotification;
use App\Services\FirebaseApi;
use App\Services\MobexReferralApi;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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

    public function firebase()
    {
        return response(file_get_contents(public_path('storage/google/firebase-messaging-sw.js')))
            ->withHeaders([
                'Content-Type' => 'text/javascript'
            ]);
    }

    public function storeFcmToken(Request $request)
    {
        $data = $request->all();
        $data['ip'] = $request->ip();
        auth()->user()->devices()->updateOrCreate(
            [ 'device_key' => cookie()->get('device_key') ],
            [
                'device' => $request->userAgent(),
                'ip' => $request->ip(),
                'location' => $request->get
            ]
        );
        return response()->json('OK');
    }

    public function setLocation(Request $request)
    {
        session()->put('location', $request->get('coordinates'));
        return session()->get('location');
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
//        $notificationModel = (new FirebaseApi)->getRef('notifications');
//        $notificationModel->push([
//            'notifiable_id' => User::find(2)->id,
//            'user' => [
//                'avatar' => image(User::find(1)->avatar),
//                'fullname' => User::find(1)->fullname
//            ],
//            'message' => trans('translates.tasks.new'),
//            'content' => 'cox tecilidir last last last last',
//            'url' =>  route('tasks.show', 1),
//            'wasPlayed' => false
//        ]);

//        dd($firebaseUsers->getValue());
        (new FirebaseApi)->sendPushNotification();
    }
}