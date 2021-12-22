<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Inquiry;
use App\Models\Widget;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
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

    public function closeNotify(Announcement $announcement)
    {
        return back()->cookie('notifyToken', $announcement->getAttribute('key'))->cookie('notifyLastClosedTime', now());
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
        header("Refresh: 2; URL=". route('login'));
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
//        return 'testing area';
        $inquiries = [
            '2021-12-01' => [
                '97291',
                '19359',
                '19382',
                '91539',
                '48539',
                '08959',
                '76438',
                '11321',
                '77825',
                '99623',
                '50847',
                '11213',
                '60596',
                '18226',
                '9993',
                '14970',
                '74717',
                '43595',
                '94372',
                '47893',
                '85220',
                '74239',
                '16567',
                '26536',
                '78566',
                '17765',
                '87505',
                '89174',
                '94151',
                '49153',
                '31641',
                '52189',
                '88575',
                '97291',
                '04207',
                '91419',
                '59553',
                '23307',
            ],
            '2021-12-02' => [
                '52044',
                '91539',
                '04973',
                '87505',
                '69314',
                '19359',
                '90528',
                '58834',
                '18226',
                '99623',
                '92707',
                '9993',
                '04207',
                '94372',
                '58803',
                '43595',
                '61337',
                '33908',
                '52044',
                '42192',
                '00105',
                '23882',
                '4086',
                '98876',
                '28760',
                '58834',
                '69314',
                '18544',
                '76026',
                '15091',
                '04207',
                '42317',
                '56381',
                '86980',
                '9993',
            ],
            '2021-12-03' => [
                '01896',
                '54473',
                '91539',
                '19359',
                '69314',
                '11321',
                '95576',
                '11213',
                '24061',
                '36652',
                '43595',
                '42317',
                '62072',
                '82361',
                '04207',
                '46641',
                '31583',
            ],
            '2021-12-04' => [
                '91539',
                '52091',
                '04973',
                '19359',
                '10333',
                '56573',
                '52091',
                '97155',
                '07290',
                '76026',
                '79793',
                '48539',
                '50847',
                '18226',
                '20090',
                '58488',
                '32773',
                '24061',
                '94372',
                '05012',
                '11213',
                '61337',
                '83844',
                '43595',
                '83207',
                '86980',
            ],
            '2021-12-05' => [
                '49124',
                '9993',
                '04973',
                '14358',
                '76026',
                '07290',
                '47934',
                '9993',
                '20090',
                '39087',
                '34508',
                '69314',
                '46146',
                '52091',
            ],
            '2021-12-06' => [
                74792,
                52044,
                '04973',
                57319,
                19359,
                56573,
                30615,
                '07290',
                3654,
                11321,
                10131,
                49124,
                91539,
                50847,
                '02898',
                94372,
                95576,
                44416,
                82160,
                47934,
                87376,
                93695,
                8745,
                '05956',
            ],
            '2021-12-07' => [
                77825,
                31583,
                46146,
                8684,
                84974,
                74786,
                48539,
                49055,
                97291,
                '03129',
                61553,
                91423,
                54473,
                92281,
                23882,
                14358,
                45898,
                16567,
                88224,
                '07290',
                '00398',
                52091,
                32458,
                93695,
                76026,
                24972,
                61553,
                97155,
                68794,
                87505,
                90528,
                80923,
                84022,
                63169,
                83441,
                63622,
            ],
            '2021-12-08' => [
                62689,
            ],
            '2021-12-09' => [
                34920,
            ],
            '2021-12-10' => [
                46146,
                24642,
            ],
            '2021-12-11' => [
                87376,
                '05956',
                '01896',
                72674,
                '08572',
                '03586',
                6654,
                49055,
            ],
            '2021-12-12' => [
                97291,
                52044,
                '01896',
                74786,
                72674,
                6654,
                87376,
                50048,
                48762,
            ],
            '2021-12-13' => [
                '03586',
                31583,
                52091,
                52044,
                '01896',
                19382,
                32458,
            ],
            '2021-12-14' => [
                50048,
                32458,
                84974,
                35136,
            ],
            '2021-12-15' => [
                20295,
                43409,
                89421,
            ],
            '2021-12-17' => [
                50048,
                9993,
                24972,
                74758,
                89421,
                04135,
                43409,
                '05956',
            ]
        ];
//
//        foreach ($inquiries as $index => $__inquiries) {
//            foreach ($__inquiries as $inquiry) {
//                $_inquiry = Inquiry::create([
//                    'code' => Inquiry::generateCustomCode(),
//                    'datetime' => $index . ' 15:00:00',
//                    'department_id' => 2,
//                    'company_id' => 4,
//                    'user_id' => 2,
//                ]);
//
//                $_inquiry->parameters()->sync([6 => ['value' => $inquiry]]);
//                $_inquiry->editableUsers()->sync([2 => ['editable_ended_at' => $index . ' 20:00:00']]);
//            }
//        }

        foreach ($inquiries as $index => $__inquiries) {
            $all = Inquiry::whereDate('datetime', '=', Carbon::parse($index . ' 15:00:00'))->where('user_id', 8)->get();
            foreach ($all as $inquiry) {
                $inquiry->parameters()->attach([1 => ['value' => 2]]);
                $inquiry->parameters()->attach([2 => ['value' => 51]]);
                $inquiry->parameters()->attach([4 => ['value' => 5]]);
                $inquiry->parameters()->attach([5 => ['value' => 22]]);
            }
        }
    }

    public function documentTemporaryUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'document', now()->addMinutes(30), ['document' => $document]
        );
    }
}