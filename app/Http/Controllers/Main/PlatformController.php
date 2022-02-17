<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Inquiry;
use App\Models\Service;
use App\Models\Task;
use App\Models\User;
use App\Models\Widget;
use App\Models\Work;
use App\Services\OpenWeatherApi;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\In;

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
        return view('pages.main.welcome');
    }

    public function deactivated()
    {
        if(!auth()->user()->isDisabled()){
            return redirect()->route('dashboard', [
                'widgets' => Widget::isActive()->oldest('order')->get()
            ]);
        }

        return view('pages.main.deactivated');
    }

    public function dashboard(): View
    {
        $usersCount = User::count();
        $worksCount = Work::count();
        $inquiriesCount = Inquiry::count();
        $tasksCount = Task::count();

        $weather = \Cache::remember('open_weather', 7200, function () {
            return (new OpenWeatherApi())->location(40.4093, 49.8671)->send();
        });

        $getData = fn($count, $total, $text) => (object)[
            'total' => $total,
            'percentage' => $total == 0 ? 0 : $count/$total * 100,
            'text' => $text
        ];

        $statistics = [
            (object)[
                'title' => __('translates.widgets.number_of_users'),
                'color' => 'tale',
                'data' => $getData(User::isActive()->count(), $usersCount, __('translates.users.statuses.active')),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => __('translates.widgets.number_of_works'),
                'color' => 'dark-blue',
                'data' => $getData(Work::isVerified()->count(), $worksCount, __('translates.columns.verified')),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => __('translates.widgets.number_of_inquiries'),
                'color' => 'light-blue',
                'data' => $getData(Inquiry::whereHas('parameters', fn($q) => $q->whereId(Inquiry::ACTIVE))->count(), $inquiriesCount, __('translates.users.statuses.active')),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => __('translates.widgets.number_of_tasks'),
                'color' => 'light-danger',
                'data' => $getData(Task::newTasks()->count(), $tasksCount, __('translates.tasks.list.to_do')),
                'class' => ''
            ],
        ];

        return view('pages.main.dashboard', [
            'widgets'    => Widget::isActive()->oldest('order')->get(),
            'tasksCount' => auth()->user()->tasks()->newTasks()->count(),
            'statistics' => $statistics,
            'weather' => $weather
        ]);
    }

    public function languageSelector()
    {
        return view('auth.lang-selector');
    }

    public function test()
    {
        abort_if(auth()->user()->isNotDeveloper(), 403);

        return 'test';
    }

    public function documentTemporaryUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'document', now()->addMinutes(30), ['document' => $document]
        );
    }
}