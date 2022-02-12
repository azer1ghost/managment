<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Inquiry;
use App\Models\Task;
use App\Models\User;
use App\Models\Widget;
use App\Models\Work;
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

        $getData = fn($count, $total, $text) => (object)[
            'total' => $total,
            'percentage' => $count/$total * 100,
            'text' => $text
        ];

        $statistics = [
            (object)[
                'title' => 'Number of users',
                'color' => 'tale',
                'data' => $getData(User::isActive()->count(), $usersCount, 'Active'),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => 'Number of works',
                'color' => 'dark-blue',
                'data' => $getData(Work::isVerified()->count(), $worksCount, 'Verified'),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => 'Number of inquiries',
                'color' => 'light-blue',
                'data' => $getData(Inquiry::whereHas('parameters', fn($q) => $q->whereId(Inquiry::ACTIVE))->count(), $inquiriesCount, 'Active'),
                'class' => 'mb-4'
            ],
            (object)[
                'title' => 'Number of tasks',
                'color' => 'light-danger',
                'data' => $getData(Task::newTasks()->count(), $tasksCount, 'To do'),
                'class' => ''
            ],
        ];

        return view('pages.main.dashboard', [
            'widgets'    => Widget::isActive()->oldest('order')->get(),
            'tasksCount' => auth()->user()->tasks()->newTasks()->count(),
            'statistics' => $statistics
        ]);
    }

    public function languageSelector()
    {
        return view('auth.lang-selector');
    }

    public function test()
    {
        abort_if(auth()->user()->isNotDeveloper(), 403);

        return 'testing area';
    }

    public function documentTemporaryUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'document', now()->addMinutes(30), ['document' => $document]
        );
    }
}