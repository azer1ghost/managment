<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Seçilmiş tarix aralığında yaradılmış müştərilər, son iş tarixləri və filialları.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $createdAt = $request->get('created_at')
            ?: now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');

        [$from, $to] = array_pad(explode(' - ', $createdAt), 2, null);
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to ?? $from)->endOfDay();

        $workStats = function ($query) {
            $query->withCount('works')
                ->withMin('works as first_work_at', 'created_at')
                ->withMax('works as last_work_at', 'created_at');
        };

        $clients = Client::query()
            ->whereNull('client_id')
            ->whereBetween('created_at', [$from, $to]);

        $workStats($clients);

        $clients = $clients
            ->with(['clients' => function ($query) use ($workStats) {
                $workStats($query);
                $query->orderBy('created_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        return view('pages.client-report.index', [
            'clients' => $clients,
            'filters' => ['created_at' => $createdAt],
            'types' => [
                Client::LEGAL => trans('translates.general.legal'),
                Client::PHYSICAL => trans('translates.general.physical'),
                Client::FOREIGNPHYSICAL => trans('translates.general.foreignphysical'),
                Client::FOREIGNLEGAL => trans('translates.general.foreignlegal'),
            ],
        ]);
    }
}
