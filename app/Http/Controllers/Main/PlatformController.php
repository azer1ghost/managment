<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Widget;
use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['welcome', 'downloadBat']]);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
//        $inquiriesToday = Inquiry::IsReal()->whereDate('datetime',  Carbon::today())->count();
//        $inquiriesMonth = Inquiry::IsReal()->whereMonth('datetime', Carbon::today())->count();

        return view('panel.pages.main.dashboard', [
            'widgets' => Widget::isActive()->oldest('order')->get()
        ]);
    }

    public function languageSelector()
    {
        return view('auth.lang-selector');
    }

    public function cabinet(): View
    {
        return view('panel.pages.cabinet.index');
    }

    public function test()
    {
        $inquiries = Inquiry::select('id')->withCount([
            'parameters as status_active_count' => fn ($q) => $q->where('inquiry_parameter.value', 21),
            'parameters as status_done_count'   => fn ($q) => $q->where('inquiry_parameter.value', 22),
            'parameters as status_rejected_count'   => fn ($q) => $q->where('inquiry_parameter.value', 23),
            'parameters as status_incompatible_count'   => fn ($q) => $q->where('inquiry_parameter.value', 24),
            'parameters as status_unreachable_count'   => fn ($q) => $q->where('inquiry_parameter.value', 25),
        ])->get()->toArray();

        $new = [0, 0, 0, 0, 0];

        foreach ($inquiries as $item){
            $new[0] += $item['status_active_count'];
            $new[1] += $item['status_done_count'];
            $new[2] += $item['status_rejected_count'];
            $new[3] += $item['status_incompatible_count'];
            $new[4] += $item['status_unreachable_count'];
        }
        return [
            'items' => $new,
            'total' => Inquiry::count(),
            'keys' => [
                'Active',
                'Done',
                'Rejected',
                'Incompatible',
                'Unreachable'
            ]
        ];
    }
}