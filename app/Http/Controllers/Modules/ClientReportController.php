<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Department;
use App\Models\Service;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Seçilmiş tarix aralığında yaradılmış müştərilər, onların işləri,
     * son iş tarixi və işlərin hansı filialda (şöbədə) görüldüyü.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $defaultRange = now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');

        $filters = [
            'created_at'      => $request->get('created_at') ?: $defaultRange,
            'check_work_date' => $request->has('check_work_date'),
            'work_date'       => $request->get('work_date') ?: $defaultRange,
            'search'          => $request->get('search'),
            'type'            => $request->get('type'),
            'active'          => $request->get('active'),
            'departments'     => array_values(array_filter((array) $request->get('departments', []), 'is_numeric')),
            'branches'        => array_values(array_filter((array) $request->get('branches', []), fn($v) => $v !== null && $v !== '')),
            'service'         => $request->get('service'),
            'status'          => $request->get('status'),
            'coordinator'     => $request->get('coordinator'),
            'sale'            => $request->get('sale'),
            'company'         => $request->get('company'),
            'has_works'       => $request->get('has_works'), // '' | 'yes' | 'no'
            'sort'            => $request->get('sort', 'created_desc'),
            'limit'           => $request->get('limit', 50),
        ];

        $createdRange = $this->range($filters['created_at']);
        $workRange = $filters['check_work_date'] ? $this->range($filters['work_date']) : null;

        // İş filtrləri — müştəri seçimində, saylarda və filial bölgüsündə eyni şərtlər
        $workConstraint = function ($query) use ($filters, $workRange) {
            $query->when($filters['branches'], fn($q) => $q->whereIn('works.service_id',
                    Service::query()->whereIn('detail', $filters['branches'])->select('id')))
                ->when($filters['departments'], fn($q) => $q->whereIn('works.department_id', $filters['departments']))
                ->when(is_numeric($filters['service']), fn($q) => $q->where('works.service_id', $filters['service']))
                ->when(is_numeric($filters['status']), fn($q) => $q->where('works.status', $filters['status']))
                ->when($workRange, fn($q) => $q->whereBetween('works.created_at', $workRange));
        };
        $hasWorkFilter = $filters['branches'] || $filters['departments'] || is_numeric($filters['service'])
            || is_numeric($filters['status']) || $workRange;

        $clients = Client::query()
            ->with(['coordinators:users.id,users.name,users.surname', 'sales:users.id,users.name,users.surname', 'companies:companies.id,companies.name'])
            ->whereNull('client_id')
            ->whereBetween('created_at', $createdRange)
            ->when($filters['search'], function ($q, $search) {
                $q->where(fn($q) => $q->where('fullname', 'LIKE', "%{$search}%")->orWhere('voen', 'LIKE', "%{$search}%"));
            })
            ->when(is_numeric($filters['type']), fn($q) => $q->where('type', (int) $filters['type']))
            ->when(is_numeric($filters['active']), fn($q) => $q->where('active', (int) $filters['active']))
            ->when(is_numeric($filters['coordinator']), fn($q) => $q->whereHas('coordinators', fn($q) => $q->where('users.id', $filters['coordinator'])))
            ->when(is_numeric($filters['sale']), fn($q) => $q->whereHas('sales', fn($q) => $q->where('users.id', $filters['sale'])))
            ->when(is_numeric($filters['company']), fn($q) => $q->whereHas('companies', fn($q) => $q->where('companies.id', $filters['company'])))
            // İş filtri verilibsə, yalnız uyğun işi olan müştərilər
            ->when($hasWorkFilter && $filters['has_works'] !== 'no', fn($q) => $q->whereHas('works', $workConstraint))
            ->when($filters['has_works'] === 'yes' && !$hasWorkFilter, fn($q) => $q->has('works'))
            ->when($filters['has_works'] === 'no', fn($q) => $q->whereDoesntHave('works', $workConstraint))
            ->withCount(['works' => $workConstraint])
            ->withMax(['works as last_work_at' => $workConstraint], 'created_at');

        switch ($filters['sort']) {
            case 'created_asc':    $clients->orderBy('created_at'); break;
            case 'last_work_desc': $clients->orderByRaw('last_work_at IS NULL, last_work_at DESC'); break;
            case 'last_work_asc':  $clients->orderByRaw('last_work_at IS NULL, last_work_at ASC'); break;
            case 'works_desc':     $clients->orderByDesc('works_count'); break;
            case 'name':           $clients->orderBy('fullname'); break;
            default:               $clients->orderByDesc('created_at');
        }

        $clients = is_numeric($filters['limit'])
            ? $clients->paginate((int) $filters['limit'])->withQueryString()
            : $clients->get();

        // Filial üzrə bölgü (filial adı xidmətin "detail" sahəsindədir):
        // hər müştərinin işi hansı filialda, neçə dəfə, son nə vaxt
        $branchQuery = Work::query()->whereIn('works.client_id', $clients->pluck('id'));
        $workConstraint($branchQuery);

        $branchStats = $branchQuery
            ->leftJoin('services', 'services.id', '=', 'works.service_id')
            ->groupBy('works.client_id', 'services.detail')
            ->orderByRaw('MAX(works.created_at) DESC')
            ->get([
                'works.client_id',
                'services.detail as branch_name',
                DB::raw('COUNT(*) as works_count'),
                DB::raw('MAX(works.created_at) as last_work_at'),
            ])
            ->groupBy('client_id');

        return view('pages.client-report.index', [
            'clients'      => $clients,
            'branchStats'  => $branchStats,
            'filters'      => $filters,
            'types' => [
                Client::LEGAL           => trans('translates.general.legal'),
                Client::PHYSICAL        => trans('translates.general.physical'),
                Client::FOREIGNPHYSICAL => trans('translates.general.foreignphysical'),
                Client::FOREIGNLEGAL    => trans('translates.general.foreignlegal'),
            ],
            'statuses'     => trans('translates.work_status'),
            'departments'  => Department::whereIn('id', Work::query()->distinct()->select('department_id'))
                ->orderBy('name')->get(['id', 'name']),
            'services'     => Service::orderBy('detail')->get(['id', 'name', 'detail']),
            'branches'     => Service::query()->whereNotNull('detail')->where('detail', '!=', '')
                ->distinct()->orderBy('detail')->pluck('detail'),
            'companies'    => Company::get(['id', 'name']),
            'coordinators' => User::isActive()->where('department_id', Department::COORDINATOR)->get(['id', 'name', 'surname']),
            'sales'        => User::isActive()->where('department_id', Department::SALES)->get(['id', 'name', 'surname']),
        ]);
    }

    private function range(string $value): array
    {
        [$from, $to] = array_pad(explode(' - ', $value), 2, null);

        return [Carbon::parse($from)->startOfDay(), Carbon::parse($to ?? $from)->endOfDay()];
    }
}
