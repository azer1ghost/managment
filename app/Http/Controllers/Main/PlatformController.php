<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Inquiry;
use App\Models\SalesActivity;
use App\Models\User;
use App\Models\Widget;
use App\Services\CacheService;
use App\Services\ExchangeRatesApi;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    /**
     * @var CacheService $cacheService
     */
    private CacheService $cacheService;

    /**
     * @var ExchangeRatesApi $exchangeRatesApi
     */
    private ExchangeRatesApi $exchangeRatesApi;

    /**
     * @param CacheService $cacheService
     */
    public function __construct(CacheService $cacheService, ExchangeRatesApi $exchangeRatesApi)
    {
        $this->middleware('auth', ['except'=> ['welcome', 'downloadBat', 'documentTemporaryUrl']]);
        $this->cacheService = $cacheService;
        $this->exchangeRatesApi = $exchangeRatesApi;
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
        header("Refresh: 1; URL=". route('login'));
        return view('pages.main.welcome');
    }

    public function deactivated()
    {
        if(!auth()->user()->isDisabled()){
            return $this->dashboard();
        }

        return view('pages.main.deactivated');
    }

    public function dashboard(): View
    {
        $newCustomer = Inquiry::isReal()->where('user_id', auth()->id())->monthly()->whereHas('options', function ($q) {
            $q->where('inquiry_parameter.value', Inquiry::NEWCUSTOMER);
        })->count();
        $recall = Inquiry::isReal()->where('user_id', auth()->id())->monthly()->whereHas('options', function ($q) {
            $q->where('inquiry_parameter.value', Inquiry::RECALL);
        })->count();
        $meetings = SalesActivity::query()
            ->where('user_id', auth()->id())
            ->where('sales_activity_type_id', 1)
            ->count();

//        $currencies = [
//            'USD' => [
//                'flag' => 'dollar',
//                'value' => 0,
//            ],
//            'EUR' => [
//                'flag' => 'euro',
//                'value' => 0,
//            ],
//            'TRY' => [
//                'flag' => 'lira',
//                'value' => 0,
//            ],
//            'RUB' => [
//                'flag' => 'ruble',
//                'value' => 0,
//            ],
//        ];

//        foreach ($currencies as $currency => $value) {
//            $currencies[$currency]['value'] = $this->exchangeRatesApi->convert($currency);
//        }

        return view('pages.main.dashboard', [
            'widgets'    => Widget::isActive()->oldest('order')->get(),
            'tasksCount' => auth()->user()->tasks()->newTasks()->departmentNewTasks()->count(),
            'statistics' => $this->cacheService->getData('statistics') ?? [],
//            'weather' => $this->cacheService->getData('open_weather'),
//            'currencies' => $currencies,
            'newCustomer' => $newCustomer,
            'recall' => $recall,
            'meetings' => $meetings,
        ]);
    }

    public function languageSelector()
    {
        return view('auth.lang-selector');
    }

    public function test(User $user)
    {
        return view('pages.main.test');
    }

    public function documentTemporaryUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'document', now()->addMinutes(30), ['document' => $document]
        );
    }
}