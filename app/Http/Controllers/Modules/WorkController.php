<?php

namespace App\Http\Controllers\Modules;

use App\Events\{WorkChanged, WorkCreated, WorkReturned, WorkStatusRejected};
use App\Exports\WorksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Interfaces\WorkRepositoryInterface;
use App\Notifications\{NotifyClientDirectorSms, NotifyClientSms};
use Illuminate\Support\Str;
use App\Models\{AsanImza, Company, Department, Logistics, Service, User, Work, Client};
use App\Services\WorkIncomeService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\{RedirectResponse, Request};

class WorkController extends Controller
{
    protected WorkRepositoryInterface $workRepository;
    protected WorkIncomeService $incomeService;

    public function __construct(WorkRepositoryInterface $workRepository, WorkIncomeService $incomeService)
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
        $this->workRepository = $workRepository;
        $this->incomeService = $incomeService;
    }

    public function export(Request $request)
    {
        $filters = json_decode($request->get('filters'), true);
        $dateFilters = json_decode($request->get('dateFilters'), true);

        return (new WorksExport($this->workRepository, $filters, $dateFilters))->download('works.xlsx');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

//        $departmentRequest = Work::userCannotViewAll() ?
//            $user->getAttribute('department_id') :
//                $request->get('department_id');

        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'coordinator'=> $request->get('coordinator'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 2],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $filters['sorter_id'] = $request->get('sorter_id');
        $filters['analyst_id'] = $request->get('analyst_id');

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'paid_at' => $request->has('check-paid_at'),
            'entry_date' => $request->has('check-entry_date'),
            'injected_at' => $request->has('check-injected_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $departmentIds = [11, 12, 13, 7, 29, 22, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();
        $coordinators = Client::all();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();

        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters)->whereNotIn('status', [1, 2]);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }
        if ($request->has('check-paid_at-null')) {
            $works = $works->whereNull('paid_at');
        }

        if ($request->has('check-returned_at')) {
            $works = $works->whereNotNull('returned_at');
        }
        if ($request->filled('empty_invoice') && $request->empty_invoice == 1) {
            $works = $works->whereNull('code');
        }

        $works = $works->paginate($limit);

        return view('pages.works.index',
            compact('works', 'services', 'departments', 'users','coordinators',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
        );
    }

    public function pendingWorks(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

//        $departmentRequest = Work::userCannotViewAll() ?
//            $user->getAttribute('department_id') :
//                $request->get('department_id');

        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 3, 4, 5, 6, 7, 8],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'paid_at' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'injected_at' => $request->has('check-injected_at'),
            'entry_date' => $request->has('check-entry_date'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $departmentIds = [11, 12, 13, 7, 29, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->isActive()
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }
        if ($request->has('check-paid_at-null')) {
            $works = $works->whereNull('paid_at');
        }

        $works = $works->pending()->paginate($limit);

        return view('pages.works.pending-works',
            compact('works', 'services', 'departments', 'users',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
        );
    }

    public function financeWorks(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');
        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at_date'),
            'vat_date' => $request->get('vat_date'),
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 2, 3, 5],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }


        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'entry_date' => $request->has('check-entry_date'),
            'injected_at' => $request->has('check-injected_at'),
            'paid_at' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $departmentIds = [11, 12, 13, 7, 29, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->isActive()
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);


        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $attention = $request->query('attention'); // '', 'need', 'ok'

        $query = $this->workRepository->allFilteredWorks($filters, $dateFilters)
            // mütləq: get() YOXDUR, əvvəlcə filtr!
            ->select('works.*') // cədvəl aliasın fərqlidirsə, onu yaz
            ->addSelect(DB::raw("
            CASE 
                WHEN works.paid_at IS NULL 
                 AND works.invoiced_date IS NOT NULL
                 AND TIMESTAMPDIFF(DAY, works.invoiced_date, NOW()) >= 30
            THEN 1 ELSE 0 
            END AS need_attention
        "));

        if ($attention === 'need') {
            $query->whereNull('works.paid_at')
                ->whereNotNull('works.invoiced_date')
                ->whereRaw('TIMESTAMPDIFF(DAY, works.invoiced_date, NOW()) >= 30');
        } elseif ($attention === 'ok') {
            $query->where(function ($q) {
                $q->whereNotNull('works.paid_at')
                    ->orWhereNull('works.invoiced_date')
                    ->orWhereRaw('TIMESTAMPDIFF(DAY, works.invoiced_date, NOW()) < 30');
            });
        }



        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }

        if ($request->has('check-paid_at-null')) {
            $works = $works->whereNull('paid_at');
        }

        if ($request->has('filterByCheckbox')) {
            $totalAmountParamIds = [33, 38, 34];
            $paidAmountParamIds = [35, 37, 36];

            $works
                ->withSum(['parameters as total_sum' => function ($subQuery) use ($totalAmountParamIds) {
                    $subQuery
                        ->select(DB::raw('COALESCE(SUM(work_parameter.value), 0)'))
                        ->whereIn('parameter_id', $totalAmountParamIds);
                }], 'work_parameter.value')
                ->withSum(['parameters as paid_sum' => function ($subQuery) use ($paidAmountParamIds) {
                    $subQuery
                        ->select(DB::raw('COALESCE(SUM(work_parameter.value), 0)'))
                        ->whereIn('parameter_id', $paidAmountParamIds);
                }], 'work_parameter.value')
                ->havingRaw('total_sum > paid_sum');
        }

        $works = $works->whereIn('status', [4, 6, 7])->paginate($limit);

        if (auth()->user()->hasPermission('viewPrice-work')) {
            return view('pages.works.finance-works',
                compact('works', 'services', 'departments', 'users',
                    'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
            );
        }

        return view('errors.403');
    }

    public function plannedWorks(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [2, 3, 4, 5, 6, 7],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'injected_at' => $request->has('check-injected_at'),
            'entry_date' => $request->has('check-entry_date'),
            'invoiced_date' => $request->has('check-invoiced_date')
        ];

        $departmentIds = [11, 12, 13, 7, 29, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->isActive()
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }

        $works = $works->planned()->paginate($limit);

        return view('pages.works.planned-works',
            compact('works', 'services', 'departments', 'users',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
        );
    }
    public function incompleteWorks(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

//        $departmentRequest = Work::userCannotViewAll() ?
//            $user->getAttribute('department_id') :
//                $request->get('department_id');

        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'entry_date' => $request->has('check-entry_date'),
            'injected_at' => $request->has('check-injected_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $departmentIds = [11, 12, 13, 7, 29, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->isActive()
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();

        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters)
            ->where(function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('status', '<>', 7);
                })
                    ->orWhere('status', '<>', 7);
            });

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }
        if ($request->has('check-paid_at-null')) {
            $works = $works->whereNull('paid_at');
        }

        if ($request->has('check-returned_at')) {
            $works = $works->whereNotNull('returned_at');
        }

        $works = $works->paginate($limit);

        return view('pages.works.incomplete-works',
            compact('works', 'services', 'departments', 'users',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
        );
    }

    public function returnedWorks(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

//        $departmentRequest = Work::userCannotViewAll() ?
//            $user->getAttribute('department_id') :
//                $request->get('department_id');

        $departmentRequest = $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
            'declaration_no' => $request->get('declaration_no'),
            'transport_no' => $request->get('transport_no'),
            'department_id' => $departmentRequest,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'asan_imza_company_id' => $request->get('asan_imza_company_id'),
            'client_id' => $request->get('client_id'),
            'verified_at' => $request->get('verified_at'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status'),
            'destination' => $request->get('destination'),
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'injected_at' => $request->get('injected_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 2],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'entry_date' => $request->has('check-entry_date'),
            'injected_at' => $request->has('check-injected_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $departmentIds = [11, 12, 13, 7, 29, 30, 24, 4];


        $usersQuery = User::has('works')
            ->with('position', 'role')
            ->isActive()
            ->whereIn('department_id', $departmentIds)
            ->select(['id', 'name', 'surname', 'position_id', 'role_id']);

        $users = $usersQuery->get();

        $departments = Department::isActive()->has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $destinations = Work::destinations();

        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user) {
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters)
            ->where(function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('status', '<>', 7)
                        ->orWhere(function ($innerInnerQuery) {
                            $innerInnerQuery->where('status', 7);
                        });
                })
                    ->orWhere('status', '<>', 7);
            });

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }
        if ($request->has('check-paid_at-null')) {
            $works = $works->whereNull('paid_at');
        }

        $works = $works->whereNotNull('returned_at');

        $works = $works->paginate($limit);

        return view('pages.works.returned-works',
            compact('works', 'services', 'departments', 'users',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods', 'destinations')
        );
    }
    public function create(Request $request)
    {
        if ($request->get('id')) {

            $data = Work::whereId($request->get('id'))->first();
        } else {
            $data = null;
        }
        return view('pages.works.edit')->with([
            'action' => route('works.store'),
            'method' => 'POST',
            'data' => $data,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'destinations' => Work::destinations(),
            'departments' => Department::isActive()->get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }
    public function store(WorkRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['creator_id'] = auth()->id();

        $work = Work::create($validated);

        $parameters = [];
        foreach ($request->input('parameters', []) as $key => $parameter) {
            if ($parameter === null || $parameter === '') continue;
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);


        if (in_array($request->get('service_id'), [5, 6, 31, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41, 54, 53])) {
            $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT);
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => $amount]);
            
            // VAT hesablaması - Company-nin has_no_vat statusuna görə
            $asanImza = $work->asanImza;
            $vat = 0;
            if ($asanImza && !$asanImza->hasNoVat() && $amount !== null) {
                $vat = round($amount * 0.18, 2);
            }
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);
        } else if (in_array($request->get('service_id'), [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
            $client = $work->getRelationValue('client');
            $mainPaper = $client->getAttribute('main_paper');
            $amount = null;
            
            if ($mainPaper > 0) {
                $amount = (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($mainPaper * $work->getParameter($work::MAINPAGE));
            } else {
                $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $mainPaper;
            }
            
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => $amount]);
            
            // VAT hesablaması - Company-nin has_no_vat statusuna görə
            $asanImza = $work->asanImza;
            $vat = 0;
            if ($asanImza && !$asanImza->hasNoVat() && $amount !== null) {
                $vat = round($amount * 0.18, 2);
            }
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);
        } else if (in_array($request->get('service_id'), [2])) {
            $client = $work->getRelationValue('client');
            $qibPaper = $client->getAttribute('qibmain_paper');
            $amount = null;
            
            if ($qibPaper > 0) {
                $amount = (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($qibPaper * $work->getParameter($work::MAINPAGE));
            } else {
                $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $qibPaper;
            }
            
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => $amount]);
            
            // VAT hesablaması - Company-nin has_no_vat statusuna görə
            $asanImza = $work->asanImza;
            $vat = 0;
            if ($asanImza && !$asanImza->hasNoVat() && $amount !== null) {
                $vat = round($amount * 0.18, 2);
            }
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);
        }

        if ((int)$work->service_id === 2) {
            $link = $work->link_key ?: (string) Str::uuid();

            DB::transaction(function () use ($work, $link) {
                $work->forceFill(['link_key' => $link])->saveQuietly();

                $pair = Work::withoutEvents(function () use ($work) {
                    return Work::create([
                        'mark'           => $work->mark,
                        'transport_no'   => $work->transport_no,
                        'declaration_no' => $work->declaration_no,
                        'creator_id'     => $work->creator_id,
                        'user_id'        => null,
                        'department_id'  => $work->department_id,
                        'service_id'     => 17,
                        'client_id'      => $work->client_id,
                        'status'         => Work::PLANNED,
                        // 'link_key' => $link  // bəzən $fillable səbəbindən yazılmaya bilər, ona görə 3-cü addımda məcburi yazırıq
                    ]);
                });

                Work::withoutEvents(function () use ($pair, $link) {
                    $pair->forceFill(['link_key' => $link])->saveQuietly();
                });

                event(new WorkCreated($pair));
            });
        }

        return redirect()
            ->route('works.edit', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }

    public function show(Work $work)
    {
        return view('pages.works.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $work,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'destinations' => Work::destinations(),
            'departments' => Department::get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function edit(Work $work)
    {
        return view('pages.works.edit')->with([
            'action' => route('works.update', $work),
            'method' => 'PUT',
            'data' => $work,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'destinations' => Work::destinations(),
            'departments' => Department::get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {
        $client = Client::where('id', $request->client_id)->first();
        $firstAsan = 0;
        if (is_null($work->getAttribute('asan_imza_id'))) {
            $firstAsan = 1;
        }

        $serviceText = trim($work->getRelationValue('service')->getAttribute('name'));
        $clientText = trim($client->getAttribute('fullname'));
        $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', 'Ə', 'ə');
        $replace = array('C', 'c', 'G', 'g', 'i', 'I', 'O', 'o', 'S', 's', 'U', 'u', 'E', 'e');
        $serviceName = str_replace($search, $replace, $serviceText);
        $clientName = str_replace($search, $replace, $clientText);
        $message = 'Deyerli ' . $clientName . ' sizin ' . $serviceName . ' uzre isiniz tamamlandi. ' . $work->getAttribute('created_at')->format('d/m/y') . ' https://my.mobilgroup.az/cs?url=mb-sat -linke kecid ederek xidmet keyfiyyetini deyerlendirmeyinizi xahis edirik!';

            if ($request->status == $work::DONE && $client->getAttribute('send_sms') == 1) {
                if (!empty($client->getAttribute('phone1')) && !in_array($work->getAttribute('service_id'), [62, 61, 54, 53])) {
                    (new NotifyClientSms($message))->toSms($client)->send();
                }
            }

        $validated = $request->validated();
        if ($work->getAttribute('datetime') == null ) {

            $validated['datetime'] = $request->get('status') == $work::DONE ? now() : NULL;

        }else {
            $validated['datetime'] = $request->get('datetime');
        }

        if ($work->getAttribute('injected_at') == null && $request->get('status') == $work::INJECTED) {
            $validated['injected_at'] = now();
        }
        if ($work->getAttribute('resume_date') == null && $request->get('status') == $work::STARTED) {
            $validated['resume_date'] = now();
        }

        $validated['verified_at'] = $request->has('verified') && !$request->has('rejected') ? now() : NULL;

        if ($work->getAttribute('returned_at') == null && ($request->get('status') == 5) && !$request->has('rejected')) {
            $validated['returned_at'] = now();
        }
        if ($work->getAttribute('entry_date') == null && in_array($request->get('status'), [3, 4, 6, 7]) && !$request->has('rejected')) {
            $validated['entry_date'] = now();
        }
        if ((!$request->has('paid_check') && $request->has('rejected') && $request->has('paid_at')) || (!$request->has('paid_check') && $request->has('paid_at'))) {
            $validated['paid_at'] = null;
        } elseif ($request->has('paid_check') && !$request->has('rejected') && !$request->has('paid_at')) {
            $validated['paid_at'] = now();
        } elseif ($request->has('paid_at')) {
            $validated['paid_at'] = $request->get('paid_at');
        }

        if ((!$request->has('vat_paid_check') && $request->has('rejected') && $request->has('vat_date')) || (!$request->has('vat_paid_check') && $request->has('vat_date'))) {
            $validated['vat_date'] = null;
        } elseif ($request->has('vat_paid_check') && !$request->has('rejected') && !$request->has('vat_date')) {
            $validated['vat_date'] = now();
        } elseif ($request->has('vat_date')) {
            $validated['vat_date'] = $request->get('vat_date');
        }

        if ($request->has('mark')) {
            $updatedMark = $request->input('mark');

            $nextWorkId = $work->id + 1;
            $nextWork = Work::find($nextWorkId);

            if ($nextWork) {
                $nextWork->update(['mark' => $updatedMark]);
            }
        }
        $oldStatus = $work->getAttribute('status');

        if ($work->getAttribute('status') == $work::REJECTED && !$request->has('rejected')) {
            $status = $validated['status'] ?? Work::PENDING;
        } else {
            if ($request->has('rejected')) {
                $status = Work::REJECTED;
            } else {
                $status = $validated['status'] ?? $work->getAttribute('status');
            }
        }

        $validated['status'] = $status;
        $parameters = $request->get('parameters');

        // Store old payment dates before update
        $oldPaidAt = $work->paid_at;
        $oldVatDate = $work->vat_date;

        $work->update($validated);

        if (!empty($work->link_key)) {
            $newTransportNo   = $validated['transport_no']   ?? $work->transport_no;
            $newDeclarationNo = $validated['declaration_no'] ?? $work->declaration_no;

            Work::withoutEvents(function () use ($work, $newTransportNo, $newDeclarationNo) {
                Work::where('link_key', $work->link_key)
                    ->where('id', '!=', $work->id)
                    ->where('status', Work::PLANNED)
                    ->update([
                        'transport_no'   => $newTransportNo,
                        'declaration_no' => $newDeclarationNo,
                    ]);
            });
        }


        if ($oldStatus == 1 && $status != 1) {
            DB::table('works')
                ->where('id', $work->id)
                ->update(['created_at' => now()]);
        }

        if ($work->getAttribute('user_id') !== null) {
            if (
                (isset($parameters[$work::GB]) && $work->getParameter($work::GB) !== null && $parameters[$work::GB] !== $work->getParameter($work::GB)) ||
                (isset($parameters[$work::CODE]) && $work->getParameter($work::CODE) !== null && $parameters[$work::CODE] !== $work->getParameter($work::CODE))
            ) {
                event(new WorkChanged($work));
            }
        }
        if ($work->getAttribute('user_id') !== null && ($request->get('status') == $work::RETURNED)) {

                event(new WorkReturned($work));
        }
        if ($work -> getAttribute('mark'))

        if ($request->has('rejected') && is_numeric($work->getAttribute('user_id'))) {
            event(new WorkStatusRejected($work));
        }

        if ($request->get('status') == $work::PENDING) {
            event(new WorkCreated($work));
        }


        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

// Pivotları tam təzələmək vacibdir (cache təmizlənir)
        $work->unsetRelation('parameters');
        $work->load('parameters', 'client', 'department');

        // Create income transactions when paid_at or vat_date are set/changed
        // Reload work to get fresh values after update
        $work->refresh();
        
        // Check if paid_at was set (was null, now has value) or changed
        $newPaidAt = $work->paid_at;
        $paidAtChanged = false;
        if ($newPaidAt) {
            if ($oldPaidAt === null) {
                $paidAtChanged = true; // Was null, now set
            } else {
                // Compare dates (handle both string and Carbon instances)
                $oldDateStr = $oldPaidAt instanceof Carbon ? $oldPaidAt->format('Y-m-d') : Carbon::parse($oldPaidAt)->format('Y-m-d');
                $newDateStr = $newPaidAt instanceof Carbon ? $newPaidAt->format('Y-m-d') : Carbon::parse($newPaidAt)->format('Y-m-d');
                $paidAtChanged = ($oldDateStr !== $newDateStr);
            }
        }
        
        if ($paidAtChanged) {
            $amount = $work->getParameter(Work::AMOUNT) ?? 0;
            $paymentDate = $newPaidAt instanceof Carbon ? $newPaidAt->format('Y-m-d') : Carbon::parse($newPaidAt)->format('Y-m-d');
            // Update PAID parameter to match AMOUNT (service will create transaction if delta > 0)
            $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $amount, $paymentDate);
        }
        
        // Check if vat_date was set (was null, now has value) or changed
        $newVatDate = $work->vat_date;
        $vatDateChanged = false;
        if ($newVatDate) {
            if ($oldVatDate === null) {
                $vatDateChanged = true; // Was null, now set
            } else {
                // Compare dates (handle both string and Carbon instances)
                $oldDateStr = $oldVatDate instanceof Carbon ? $oldVatDate->format('Y-m-d') : Carbon::parse($oldVatDate)->format('Y-m-d');
                $newDateStr = $newVatDate instanceof Carbon ? $newVatDate->format('Y-m-d') : Carbon::parse($newVatDate)->format('Y-m-d');
                $vatDateChanged = ($oldDateStr !== $newDateStr);
            }
        }
        
        if ($vatDateChanged) {
            $vat = $work->getParameter(Work::VAT) ?? 0;
            $paymentDate = $newVatDate instanceof Carbon ? $newVatDate->format('Y-m-d') : Carbon::parse($newVatDate)->format('Y-m-d');
            // Update VATPAYMENT parameter to match VAT (service will create transaction if delta > 0)
            $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $vat, $paymentDate);
        }

// GB və SERVICECOUNT dəyişikliklərini yoxlamaq üçün (parametr ID-lə)
        $gbParamId = $work::GB;
        $serviceCountParamId = $work::SERVICECOUNT;

        $gbChanged = isset($validated['parameters'][$gbParamId]);
        $serviceCountChanged = isset($validated['parameters'][$serviceCountParamId]);

        if (Work::getClientServiceAmount($work) > 0 && ($firstAsan == 1 || $gbChanged || $serviceCountChanged)) {

            $serviceId = $request->get('service_id');
            $asanImzaId = $request->get('asan_imza_id');
            $client = $work->client;
            $deptId = $work->department->id;

            $amount = null;
            $vat = 0;

            // ==================== AMOUNT hesablamaları ====================

            // Sadə xidmətlər
            if (in_array($serviceId, [5, 6, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41, 54, 53])) {
                $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT);
            }

            // GB və MAINPAGE əsaslı xidmətlər
            elseif (in_array($serviceId, [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
                $mainPaper = $client->main_paper;
                if ($mainPaper > 0) {
                    if (in_array($asanImzaId, [22])) {
                        $amount = 0;

                        // Illegal məbləğ
                        if ($deptId === 12)
                            $work->parameters()->updateExistingPivot($work::ILLEGALAMOUNT, ['value' => $work->getParameter($work::GB) * 20]);
                        elseif ($deptId === 13)
                            $work->parameters()->updateExistingPivot($work::ILLEGALAMOUNT, ['value' => $work->getParameter($work::GB) * 15]);

                    } else {
                        $amount = (Work::getClientServiceAmount($work)
                                * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE)))
                            + ($mainPaper * $work->getParameter($work::MAINPAGE));
                    }
                } else {
                    $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $mainPaper;
                }
            }

            // QİB əsaslı xidmətlər
            elseif (in_array($serviceId, [2])) {
                $qibPaper = $client->qibmain_paper;
                if ($qibPaper > 0) {
                    if (in_array($asanImzaId, [22])) {
                        $amount = 0;
                    } else {
                        $amount = (Work::getClientServiceAmount($work)
                                * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE)))
                            + ($qibPaper * $work->getParameter($work::MAINPAGE));
                    }
                } else {
                    $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $qibPaper;
                }
            }

            // AMOUNT pivotunu yenilə
            if ($amount !== null) {
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => round($amount, 2)]);
            }

            // ==================== VAT hesablamaları ====================

            // Asan İmza-nın Company ID-yə görə VAT-siz olub-olmadığını yoxlayırıq
            // Əgər Company has_no_vat = true olarsa, VAT 0%, yoxdursa 18%
            $asanImza = $asanImzaId ? AsanImza::with('company')->find($asanImzaId) : null;
            if ($asanImza && $amount !== null) {
                if (!$asanImza->hasNoVat()) {
                    // Company ƏDV-li olduğu üçün 18% ƏDV hesablanır
                    $vat = round($amount * 0.18, 2);
                } else {
                    // Company ƏDV-siz olduğu üçün VAT = 0
                    $vat = 0;
                }
            }

            // VAT pivotunu yenilə
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);
        }





        return redirect()
            ->route('works.show', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }

     public function updateStatus()
    {
        $works = Work::where('status', 3)->get();

        foreach ($works as $work) {
            $workDate = $work->getAttribute('created_at');
            $now = Carbon::now();

            if ($now->diffInDays($workDate) >= 1) {
                $work->created_at = $now;
                $work->save();
            }
        }
        return redirect()->back()->with('success', 'dd');

    }
    public function updateMark(Request $request)
    {
        $selectedWorks = $request->input('works', []);

        Work::whereIn('id', $selectedWorks)->update(['status' => 2]);

        return response()->json(['success' => true]);
    }



    public function verify(Work $work)
    {
        if ($work->update(['verified_at' => now()])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function verifyFinance(Work $work)
    {
        if ($work->update(['verified_at' => now()])) {
            return back();
        }
        return response()->setStatusCode('204');
    }

    public function paid(Work $work, Request $request)
    {
        $date = $request->get('paid_at') ?? now();
        $work->update(['paid_at' => $date]);
        
        // Get amount value and update parameter using service (creates income transaction)
        $amount = $work->getParameter(Work::AMOUNT) ?? 0;
        $paymentDate = Carbon::parse($date)->format('Y-m-d');
        $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $amount, $paymentDate);
        
        return back();
    }

    public function vatPaid(Work $work, Request $request)
    {
        $date = $request->get('vatPaid_at') ?? now();
        $work->update(['vat_date' => $date]);
        
        // Get VAT value and update parameter using service (creates income transaction)
        $vat = $work->getParameter(Work::VAT) ?? 0;
        $paymentDate = Carbon::parse($date)->format('Y-m-d');
        $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $vat, $paymentDate);
        
        return back();
    }

    public function invoice(Work $work, Request $request)
    {
        $date = $request->get('invoiced_date') ?? now();
        $work->update(['invoiced_date' => $date]);
        return back();
    }

    public function changeCreate(Work $work, Request $request)
    {
        $date = $request->get('created_at') ?? now();
        $work->update(['created_at' => $date]);
        return back();
    }

    public function sumVerify(Request $request)
    {
        $err = 0;
        foreach ($request->get('works') ?? [] as $work) {
            if (!Work::find($work)->update(['verified_at' => now()])) {
                $err = 400;
            }
        }

        if ($err == 400) {
            return response()->setStatusCode('204');
        }

        return response('OK');
    }

    public function destroy(Work $work)
    {
        if ($work->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function report(Request $request)
    {
        $created_at = explode(' - ', $request->get('created_at'));

        $services = Service::whereHas('works', function ($q) use ($created_at) {
            $q->where('user_id', auth()->id())
                ->whereBetween('created_at',
                    [
                        Carbon::parse($created_at[0])->startOfDay(),
                        Carbon::parse($created_at[1])->endOfDay()
                    ]
                );
        })
            ->withCount([
                'works' => function ($q) use ($created_at) {
                    $q->whereBetween('created_at',
                        [
                            Carbon::parse($created_at[0])->startOfDay(),
                            Carbon::parse($created_at[1])->endOfDay()
                        ]
                    )->where('user_id', auth()->id());
                },
                'works as works_rejected' => function ($q) use ($created_at) {
                    $q->whereBetween('created_at',
                        [
                            Carbon::parse($created_at[0])->startOfDay(),
                            Carbon::parse($created_at[1])->endOfDay()
                        ]
                    )->where('user_id', auth()->id())
                        ->isRejected();
                },
                'works as works_verified' => function ($q) use ($created_at) {
                    $q->whereBetween('created_at',
                        [
                            Carbon::parse($created_at[0])->startOfDay(),
                            Carbon::parse($created_at[1])->endOfDay()
                        ]
                    )->where('user_id', auth()->id())->isVerified();
                },

            ])->get();

        return view('pages.works.components.work-report')->with([
            'services' => $services,
            'user' => auth()->user(),
        ]);
    }

//    public function editable(Request $request)
//    {
//        if ($request->ajax()) {
//            $work = Work::find($request->pk);
//            $work->parameters()->updateExistingPivot($request->name, ['value' => $request->value]);
//            return response()->json(['success' => true]);
//        }
//    }

    public function editable(Request $request)
    {
        if ($request->ajax()) {
            $work = Work::with('parameters', 'client', 'department')->find($request->pk);

            // Pivot dəyəri yenilə
            $work->parameters()->updateExistingPivot($request->name, ['value' => $request->value]);

            // Cache təmizləmək üçün relation-ı yenilə
            $work->unsetRelation('parameters');
            $work->load('parameters', 'client', 'department');

            // Yenilənən parametrin ID-sini yoxla
            $paramId = (int)$request->name;

            // Hansı parametrlər dəyişəndə hesablama getsin
            $recalcParams = [
                $work::GB,
                $work::SERVICECOUNT,
                $work::MAINPAGE,
            ];

            $amount = null;
            $vat = null;

            // Əgər dəyişən bu parametrlərdən biridirsə, yenidən hesabla
            if (in_array($paramId, $recalcParams)) {
                [$amount, $vat] = $this->recalculateAmountAndVat($work);
            }

            return response()->json([
                'success' => true,
                'amount' => $amount,
                'vat' => $vat,
            ]);
        }
    }

    /**
     * Pivot məlumatlarına əsasən AMOUNT və VAT yeniləyir
     * və eyni zamanda bu dəyərləri geri qaytarır.
     */
    protected function recalculateAmountAndVat(Work $work)
    {
        $serviceId = $work->service_id;
        $asanImzaId = $work->asan_imza_id;
        $client = $work->client;
        $deptId = $work->department->id;

        $amount = null;
        $vat = 0;

        // ==================== AMOUNT hesablamaları ====================

        if (in_array($serviceId, [5, 6, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41, 54, 53])) {
            $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT);
        }

        elseif (in_array($serviceId, [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
            $mainPaper = $client->main_paper;
            if ($mainPaper > 0) {
                if (in_array($asanImzaId, [22])) {
                    $amount = 0;
                    if ($deptId === 12)
                        $work->parameters()->updateExistingPivot($work::ILLEGALAMOUNT, ['value' => $work->getParameter($work::GB) * 20]);
                    elseif ($deptId === 13)
                        $work->parameters()->updateExistingPivot($work::ILLEGALAMOUNT, ['value' => $work->getParameter($work::GB) * 15]);
                } else {
                    $amount = (Work::getClientServiceAmount($work)
                            * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE)))
                        + ($mainPaper * $work->getParameter($work::MAINPAGE));
                }
            } else {
                $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $mainPaper;
            }
        }

        elseif (in_array($serviceId, [2])) {
            $qibPaper = $client->qibmain_paper;
            if ($qibPaper > 0) {
                if (in_array($asanImzaId, [22])) {
                    $amount = 0;
                } else {
                    $amount = (Work::getClientServiceAmount($work)
                            * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE)))
                        + ($qibPaper * $work->getParameter($work::MAINPAGE));
                }
            } else {
                $amount = Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $qibPaper;
            }
        }

        if ($amount !== null) {
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => round($amount, 2)]);
        }

        // ==================== VAT hesablamaları ====================

        // Asan İmza-nın Company ID-yə görə VAT-siz olub-olmadığını yoxlayırıq
        // Əgər Company has_no_vat = true olarsa, VAT 0%, yoxdursa 18%
        $asanImza = $work->asanImza;
        if ($asanImza && $amount !== null) {
            if (!$asanImza->hasNoVat()) {
                // Company ƏDV-li olduğu üçün 18% ƏDV hesablanır
                $vat = round($amount * 0.18, 2);
            } else {
                // Company ƏDV-siz olduğu üçün VAT = 0
                $vat = 0;
            }
        }

        $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);

        return [round($amount ?? 0, 2), round($vat, 2)];
    }


    public function code(Request $request)
    {
        if ($request->ajax()) {
            $work = Work::find($request->pk);

            $work->update([$request->name => $request->value]);
            $work->update(['invoiced_date' => now()]);

            return response()->json(['success' => true]);
        }
    }

    public function paymentMethod(Request $request)
    {
        if ($request->ajax()) {
            $work = Work::find($request->id);

            $work->update(['payment_method' => $request->payment_method]);
            return response()->json(['success' => true]);
        }
    }

    public function declaration(Request $request)
    {
        if ($request->ajax()) {
            $work = Work::find($request->pk);

            $work->update([$request->name => $request->value]);

            return response()->json(['success' => true]);
        }
    }

    public function updateColor(Request $request)
    {
        $painted = $request->get('painted');
        $id = $request->get('id');

        $work = Work::findOrFail($id);

        if ($work->doc != 1) {
            return response()->json(['message' => 'error', 'reason' => 'doc değeri 1 değil'], 400);
        }

        $work->painted = $painted;
        $work->save();

        return response()->json(['message' => 'ok'], 200);
    }

    public function updateDoc(Request $request)
    {
        $doc = $request->get('doc');
        $id = $request->get('id');

        $work = Work::findOrFail($id);

        $work->doc = $doc;
        $work->save();

        return response()->json(['message' => 'ok'], 200);
    }

    public function showTotal(Request $request)
    {
        $startOfMonth = now()->firstOfMonth()->format('Y-m-d');
        $endOfMonth = now()->format('Y-m-d');

        $filters = [];

        if ($request->has('paid_at')) {
            $paid_at_range = $request->input('paid_at');
        } else {
            $paid_at_range = now()->firstOfMonth()->format('Y-m-d') . ' - ' . now()->format('Y-m-d');
        }
        if ($request->has('vat_date')) {
            $vat_date_range = $request->input('vat_date');
        } else {
            $vat_date_range = now()->firstOfMonth()->format('Y-m-d') . ' - ' . now()->format('Y-m-d');
        }

        if ($request->has('created_at')) {
            $created_at_range = $request->input('created_at');
        } else {
            $created_at_range = now()->firstOfMonth()->format('Y-m-d') . ' - ' . now()->format('Y-m-d');
        }

        if ($paid_at_range) {
            $filters['paid_at'] = $paid_at_range;
        } else {
            $filters['paid_at'] = $startOfMonth . ' - ' . $endOfMonth;
        }

        if ($vat_date_range) {
            $filters['vat_date'] = $vat_date_range;
        } else {
            $filters['vat_date'] = $startOfMonth . ' - ' . $endOfMonth;
        }

        if ($created_at_range) {
            $filters['created_at'] = $created_at_range;
        } else {
            $filters['created_at'] = $startOfMonth . ' - ' . $endOfMonth;
        }

        $works = Work::where(function($query) use ($filters) {
            $paid_at_range = $filters['paid_at'];
            $dates = explode(' - ', $paid_at_range);
            if (count($dates) === 2) {
                $query->whereBetween('paid_at', [$dates[0], $dates[1]]);
            }
        })
            ->with('parameters')
            ->get();


        $vatWorks = Work::where(function($query) use ($filters) {
            $vat_date_range = $filters['vat_date'];
            $dates = explode(' - ', $vat_date_range);
            if (count($dates) === 2) {
                $query->whereBetween('vat_date', [$dates[0], $dates[1]]);
            }
        })
            ->with('parameters')
            ->get();

        $createdWorks = Work::where(function($query) use ($filters) {
            $created_at_range = $filters['created_at'];
            $dates = explode(' - ', $created_at_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        })
            ->with('parameters')
            ->get();

        $logistics = Logistics::where(function($query) use ($filters) {
            $created_at_range = $filters['paid_at'];
            $dates = explode(' - ', $created_at_range);
            if (count($dates) === 2) {
                $query->whereBetween('paid_at', [$dates[0], $dates[1]]);
            }
        })
            ->with('parameters')
            ->get();

        $logSales = 0;
        $logPurchase = 0;
        $totalIllegalAmount = 0;
        $totalAmount = 0;
        $totalVat = 0;
        $totalPaidAmount = 0;
        $totalPaidVat = 0;
        $totalPaidIllegal = 0;
        $totalAll = 0;
        $totalPaidAll = 0;

        $AMBGIPaid = $works->where('department_id', 11);
        $BBGIPaid = $works->where('department_id', 12);
        $HNBGIPaid = $works->where('department_id', 13);

        $AMBGIVatPaid = $vatWorks->where('department_id', 11);
        $BBGIVatPaid = $vatWorks->where('department_id', 12);
        $HNBGIVatPaid = $vatWorks->where('department_id', 13);

        $AMBGI = $createdWorks->where('department_id', 11);
        $BBGI = $createdWorks->where('department_id', 12);
        $HNBGI = $createdWorks->where('department_id', 13);

        foreach ($logistics as $log){
            $logSales += round($log->getParameter(Logistics::SALESPAID), 2) ?? 0;
            $logPurchase += round($log->getParameter(Logistics::PURCHASEPAID), 2) ?? 0;
        }
        foreach ($works as $work){
            $totalPaidAmount += round($work->getParameter(Work::PAID), 2) ?? 0;
            $totalPaidIllegal += round($work->getParameter(Work::ILLEGALPAID), 2) ?? 0;
        }
        foreach ($vatWorks as $vatWork){
            $totalPaidVat += round($vatWork->getParameter(Work::VATPAYMENT), 2) ?? 0;
        }
        foreach ($createdWorks as $createdWork){
            $totalIllegalAmount += round($createdWork->getParameter(Work::ILLEGALAMOUNT), 2) ?? 0;
            $totalAmount += round($createdWork->getParameter(Work::AMOUNT), 2) ?? 0;
            $totalVat += round($createdWork->getParameter(Work::VAT), 2) ?? 0;
            $totalAll = round($totalIllegalAmount + $totalAmount + $totalVat, 2);
        }
        $totalPaidAll = round($totalPaidAmount + $totalPaidVat + $totalPaidIllegal, 2);
        $AMBGIPaidIllegal = round($AMBGIPaid->sum->getParameter(Work::ILLEGALPAID), 2);
        $AMBGIPaidVat = round($AMBGIVatPaid->sum->getParameter(Work::VATPAYMENT), 2);
        $AMBGIPaidAmount = round($AMBGIPaid->sum->getParameter(Work::PAID), 2);

        $BBGIPaidIllegal = round($BBGIPaid->sum->getParameter(Work::ILLEGALPAID), 2);
        $BBGIPaidVat = round($BBGIVatPaid->sum->getParameter(Work::VATPAYMENT), 2);
        $BBGIPaidAmount = round($BBGIPaid->sum->getParameter(Work::PAID), 2);

        $HNBGIPaidIllegal = round($HNBGIPaid->sum->getParameter(Work::ILLEGALPAID), 2);
        $HNBGIPaidVat = round($HNBGIVatPaid->sum->getParameter(Work::VATPAYMENT), 2);
        $HNBGIPaidAmount = round($HNBGIPaid->sum->getParameter(Work::PAID), 2);

        $AMBGIIllegal = round($AMBGI->sum->getParameter(Work::ILLEGALAMOUNT), 2);
        $AMBGIVat = round($AMBGI->sum->getParameter(Work::VAT), 2);
        $AMBGIAmount = round($AMBGI->sum->getParameter(Work::AMOUNT), 2);

        $BBGIIllegal = round($BBGI->sum->getParameter(Work::ILLEGALAMOUNT), 2);
        $BBGIVat = round($BBGI->sum->getParameter(Work::VAT), 2);
        $BBGIAmount = round($BBGI->sum->getParameter(Work::AMOUNT), 2);

        $HNBGIIllegal = round($HNBGI->sum->getParameter(Work::ILLEGALAMOUNT), 2);
        $HNBGIVat = round($HNBGI->sum->getParameter(Work::VAT), 2);
        $HNBGIAmount = round($HNBGI->sum->getParameter(Work::AMOUNT), 2);

        $totalAMBGI = round($AMBGIPaidIllegal + $AMBGIPaidVat + $AMBGIPaidAmount, 2);
        $totalBBGI = round($BBGIPaidIllegal + $BBGIPaidVat + $BBGIPaidAmount, 2);
        $totalHNBGI = round($HNBGIPaidIllegal + $HNBGIPaidVat + $HNBGIPaidAmount, 2);

        $totalSalesAMBGI = round($AMBGIIllegal + $AMBGIVat + $AMBGIAmount, 2);
        $totalSalesBBGI = round($BBGIIllegal + $BBGIVat + $BBGIAmount, 2);
        $totalSalesHNBGI = round($HNBGIIllegal + $HNBGIVat + $HNBGIAmount, 2);


        function calculateCashTotal($works, $asanImzaIds) {
            return $works->whereIn('asan_imza_id', $asanImzaIds)
                ->where('payment_method', 1)
                ->sum(function($item) {
                    return $item->getParameter(Work::ILLEGALPAID) + $item->getParameter(Work::VATPAYMENT) + $item->getParameter(Work::PAID);
                });
        }
        function calculateBankTotal($works, $asanImzaIds) {
            return $works->whereIn('asan_imza_id', $asanImzaIds)
                ->where('payment_method', 2)
                ->sum(function($item) {
                    return $item->getParameter(Work::ILLEGALPAID) + $item->getParameter(Work::VATPAYMENT) + $item->getParameter(Work::PAID);
                });
        }
        $companyIdToCategory = [
            3  => 'MOBIL',
            6  => 'GARANT',
            10 => 'MIND',
            11 => 'RIGEL',
            12 => 'ASAZA',
            15 => 'TEDORA',
            14 => 'DECLARE',
            4 => 'MOBEX',
        ];

        $asanImzas = AsanImza::all();

        $CompanyCategories = [];

        foreach ($asanImzas as $asanImza) {
            $companyId = $asanImza->company_id;

            if (isset($companyIdToCategory[$companyId])) {
                $category = $companyIdToCategory[$companyId];
                $CompanyCategories[$category][] = $asanImza->id;
            }
        }

//        dd($CompanyCategories);

        $AMBGICashTotals = [];
        $AMBGIBankTotals = [];
        foreach ($CompanyCategories as $category => $asanImzaIds) {
            $AMBGICashTotals[$category] = calculateCashTotal($AMBGIPaid, $asanImzaIds);
            $AMBGIBankTotals[$category] = calculateBankTotal($AMBGIPaid, $asanImzaIds);
        }

        $BBGICashTotals = [];
        $BBGIBankTotals = [];
        foreach ($CompanyCategories as $category => $asanImzaIds) {
            $BBGICashTotals[$category] = calculateCashTotal($BBGIPaid, $asanImzaIds);
            $BBGIBankTotals[$category] = calculateBankTotal($BBGIPaid, $asanImzaIds);
        }

        $HNBGIBankTotals = [];
        $HNBGICashTotals = [];
        foreach ($CompanyCategories as $category => $asanImzaIds) {
            $HNBGICashTotals[$category] = calculateCashTotal($HNBGIPaid, $asanImzaIds);
            $HNBGIBankTotals[$category] = calculateBankTotal($HNBGIPaid, $asanImzaIds);
        }

        $RigelTotal   = ($AMBGICashTotals['RIGEL'] ?? 0) + ($BBGICashTotals['RIGEL'] ?? 0) + ($HNBGICashTotals['RIGEL'] ?? 0);
        $DeclareTotal = ($AMBGICashTotals['DECLARE'] ?? 0) + ($BBGICashTotals['DECLARE'] ?? 0) + ($HNBGICashTotals['DECLARE'] ?? 0);
        $GarantTotal  = ($AMBGICashTotals['GARANT'] ?? 0) + ($BBGICashTotals['GARANT'] ?? 0) + ($HNBGICashTotals['GARANT'] ?? 0);
        $MobilTotal   = ($AMBGICashTotals['MOBIL'] ?? 0) + ($BBGICashTotals['MOBIL'] ?? 0) + ($HNBGICashTotals['MOBIL'] ?? 0);
        $TedoraTotal  = ($AMBGICashTotals['TEDORA'] ?? 0) + ($BBGICashTotals['TEDORA'] ?? 0) + ($HNBGICashTotals['TEDORA'] ?? 0);
        $MindTotal    = ($AMBGICashTotals['MIND'] ?? 0) + ($BBGICashTotals['MIND'] ?? 0) + ($HNBGICashTotals['MIND'] ?? 0);
        $AsazaTotal   = ($AMBGICashTotals['ASAZA'] ?? 0) + ($BBGICashTotals['ASAZA'] ?? 0) + ($HNBGICashTotals['ASAZA'] ?? 0);
        $MobexTotal   = ($AMBGICashTotals['MOBEX'] ?? 0) + ($BBGICashTotals['MOBEX'] ?? 0) + ($HNBGICashTotals['MOBEX'] ?? 0);

        $RigelBankTotal   = ($AMBGIBankTotals['RIGEL'] ?? 0) + ($BBGIBankTotals['RIGEL'] ?? 0) + ($HNBGIBankTotals['RIGEL'] ?? 0);
        $DeclareBankTotal = ($AMBGIBankTotals['DECLARE'] ?? 0) + ($BBGIBankTotals['DECLARE'] ?? 0) + ($HNBGIBankTotals['DECLARE'] ?? 0);
        $GarantBankTotal  = ($AMBGIBankTotals['GARANT'] ?? 0) + ($BBGIBankTotals['GARANT'] ?? 0) + ($HNBGIBankTotals['GARANT'] ?? 0);
        $MobilBankTotal   = ($AMBGIBankTotals['MOBIL'] ?? 0) + ($BBGIBankTotals['MOBIL'] ?? 0) + ($HNBGIBankTotals['MOBIL'] ?? 0);
        $TedoraBankTotal  = ($AMBGIBankTotals['TEDORA'] ?? 0) + ($BBGIBankTotals['TEDORA'] ?? 0) + ($HNBGIBankTotals['TEDORA'] ?? 0);
        $MindBankTotal    = ($AMBGIBankTotals['MIND'] ?? 0) + ($BBGIBankTotals['MIND'] ?? 0) + ($HNBGIBankTotals['MIND'] ?? 0);
        $AsazaBankTotal   = ($AMBGIBankTotals['ASAZA'] ?? 0) + ($BBGIBankTotals['ASAZA'] ?? 0) + ($HNBGIBankTotals['ASAZA'] ?? 0);
        $MobexBankTotal   = ($AMBGIBankTotals['MOBEX'] ?? 0) + ($BBGIBankTotals['MOBEX'] ?? 0) + ($HNBGIBankTotals['MOBEX'] ?? 0);

        $totalAMBGICash = ($AMBGICashTotals['RIGEL'] ?? 0) + ($AMBGICashTotals['DECLARE'] ?? 0) + ($AMBGICashTotals['GARANT'] ?? 0)
            + ($AMBGICashTotals['MOBIL'] ?? 0) + ($AMBGICashTotals['TEDORA'] ?? 0) + ($AMBGICashTotals['MIND'] ?? 0)
            + ($AMBGICashTotals['ASAZA'] ?? 0) + ($AMBGICashTotals['MOBEX'] ?? 0);

        $totalBBGICash = ($BBGICashTotals['RIGEL'] ?? 0) + ($BBGICashTotals['DECLARE'] ?? 0) + ($BBGICashTotals['GARANT'] ?? 0)
            + ($BBGICashTotals['MOBIL'] ?? 0) + ($BBGICashTotals['TEDORA'] ?? 0) + ($BBGICashTotals['MIND'] ?? 0)
            + ($BBGICashTotals['ASAZA'] ?? 0) + ($BBGICashTotals['MOBEX'] ?? 0);

        $totalHNBGICash = ($HNBGICashTotals['RIGEL'] ?? 0) + ($HNBGICashTotals['DECLARE'] ?? 0) + ($HNBGICashTotals['GARANT'] ?? 0)
            + ($HNBGICashTotals['MOBIL'] ?? 0) + ($HNBGICashTotals['TEDORA'] ?? 0) + ($HNBGICashTotals['MIND'] ?? 0)
            + ($HNBGICashTotals['ASAZA'] ?? 0) + ($HNBGICashTotals['MOBEX'] ?? 0);



        return view('pages.works.total',
            compact(
             'totalIllegalAmount',
             'totalAmount',
                        'totalVat',
                        'totalAll',
                        'totalPaidAmount',
                        'totalPaidVat',
                        'totalPaidIllegal',
                        'totalPaidAll',
                        'AMBGIPaidIllegal',
                        'AMBGIPaidVat',
                        'AMBGIPaidAmount',
                        'totalAMBGI',
                        'BBGIPaidIllegal',
                        'BBGIPaidVat',
                        'BBGIPaidAmount',
                        'totalBBGI',
                        'HNBGIPaidIllegal',
                        'HNBGIPaidVat',
                        'HNBGIPaidAmount',
                        'totalHNBGI',
                        'AMBGIIllegal',
                        'AMBGIVat',
                        'AMBGIAmount',
                        'totalSalesAMBGI',
                        'BBGIIllegal',
                        'BBGIVat',
                        'BBGIAmount',
                        'totalSalesBBGI',
                        'HNBGIIllegal',
                        'HNBGIVat',
                        'HNBGIAmount',
                        'totalSalesHNBGI',
                        'AMBGICashTotals',
                        'AMBGIBankTotals',
                        'BBGICashTotals',
                        'BBGIBankTotals',
                        'HNBGICashTotals',
                        'HNBGIBankTotals',
                        'RigelTotal',
                        'DeclareTotal',
                        'GarantTotal',
                        'MobilTotal',
                        'TedoraTotal',
                        'MindTotal',
                        'AsazaTotal',
                        'MobexTotal',
                        'RigelBankTotal',
                        'DeclareBankTotal',
                        'GarantBankTotal',
                        'MobilBankTotal',
                        'TedoraBankTotal',
                        'MindBankTotal',
                        'AsazaBankTotal',
                        'MobexBankTotal',
                        'totalAMBGICash',
                        'totalBBGICash',
                        'totalHNBGICash',
                        'filters',
                        'logSales',
                        'logPurchase',
//                        'dateFilters',
            ));
    }
    public function showInformation(Request $request)
    {
        $startDate = Carbon::now()->subDays(10);
        $endDate = Carbon::now();


        $works = Work::with(['client.sales', 'department', 'parameters'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();


        $groupedWorks = $works->groupBy(function ($work) {
            return $work->client->id . '-' . $work->department->id;
        });


        $formattedWorks = $groupedWorks->map(function ($group) {
            return [
                'client' => $group->first()->client,
                'department' => $group->first()->department,
                'works' => $group
            ];
        });

        return view('pages.works.information', compact('formattedWorks'));
    }

    public function companyPaymentsLastYear(Request $request)
    {
        // Tarix parametrləri JS-dən gəlir, yoxdursa default (geri uyğunluq üçün)
        $startDate = $request->input('start_date') 
            ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subYear()->startOfDay();
        
        $endDate = $request->input('end_date')
            ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfDay();

        // Subquery: hər iş üçün amount = 33+34+38 parametrlərinin cəmi
        $amountSubquery = DB::table('work_parameter')
            ->select('work_id', DB::raw('SUM(CAST(value AS DECIMAL(18,2))) AS amount'))
            ->whereIn('parameter_id', [33, 34, 38])
            ->groupBy('work_id');

        // Qaimə toplam: invoiced_date üzrə
        $qaimaTotal = DB::table('works as w')
            ->joinSub($amountSubquery, 'amt', function($join) {
                $join->on('amt.work_id', '=', 'w.id');
            })
            ->whereNotNull('w.invoiced_date')
            ->whereBetween('w.invoiced_date', [$startDate, $endDate])
            ->whereNull('w.deleted_at')
            ->sum('amt.amount');

        // Nağd toplam: created_at üzrə
        $nagdTotal = DB::table('works as w')
            ->joinSub($amountSubquery, 'amt', function($join) {
                $join->on('amt.work_id', '=', 'w.id');
            })
            ->whereBetween('w.created_at', [$startDate, $endDate])
            ->whereNull('w.deleted_at')
            ->sum('amt.amount');

        $total = ($qaimaTotal ?? 0) + ($nagdTotal ?? 0);

        // Şirkətə görə bölgü (mövcud funksionallıq üçün)
        $rows = DB::table('works as w')
            ->join('asan_imzalar as ai', 'ai.id', '=', 'w.asan_imza_id')
            ->join('companies as c', 'c.id', '=', 'ai.company_id')
            ->joinSub($amountSubquery, 'amt', function($join) {
                $join->on('amt.work_id', '=', 'w.id');
            })
            ->whereNotNull('w.asan_imza_id')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('w.invoiced_date', [$startDate, $endDate])
                      ->orWhereBetween('w.created_at', [$startDate, $endDate]);
            })
            ->whereNull('w.deleted_at')
            ->groupBy('c.id', 'c.name')
            ->select([
                'c.id as company_id',
                'c.name as company_name',
                DB::raw('SUM(amt.amount) AS total_payment')
            ])
            ->orderByDesc('total_payment')
            ->get();

        return response()->json([
            'since' => $startDate->toDateString(),
            'until' => $endDate->toDateString(),
            'qaima_total' => $qaimaTotal ?? 0,
            'nagd_total' => $nagdTotal ?? 0,
            'total' => $total,
            'data'  => $rows,
        ]);
    }

    /**
     * Fetch works for invoice finalization popup
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchInvoiceWorks(Request $request)
    {
        try {
            $workIds = $request->input('ids', []);
            $invoiceCode = $request->input('invoice_code', null);
            
            // If invoice code is provided, fetch all works with that invoice code
            if ($invoiceCode) {
                $works = Work::with(['parameters', 'service'])
                    ->where('code', $invoiceCode)
                    ->get();
            } 
            // Otherwise use provided work IDs
            else if (!empty($workIds) && is_array($workIds)) {
                $works = Work::with(['parameters', 'service'])
                    ->whereIn('id', $workIds)
                    ->get();
                
                // If we have works and want to filter by the first work's invoice code
                if ($works->isNotEmpty()) {
                    $firstWork = $works->first();
                    if ($firstWork && $firstWork->code) {
                        // Fetch all works with the same invoice code
                        $works = Work::with(['parameters', 'service'])
                            ->where('code', $firstWork->code)
                            ->get();
                    }
                }
            } else {
                return response()->json(['error' => 'No work IDs or invoice code provided'], 400);
            }

            if ($works->isEmpty()) {
                return response()->json(['error' => 'No works found'], 404);
            }

            // Get invoice code from first work
            $invoiceCode = $works->first()->code;

            // Companies for invoice company select box
            $invoiceCompanies = Company::select('id', 'name')
                ->orderBy('name')
                ->get();

            $html = view('works.partials.invoice-popup', compact('works', 'invoiceCode', 'invoiceCompanies'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Error in fetchInvoiceWorks: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete payment for a work
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completePayment(Request $request)
    {
        $workId = $request->input('id');
        $paidDate = $request->input('paid_date');
        $vatDate = $request->input('vat_date');

        if (!$workId) {
            return response()->json(['error' => 'Work ID is required'], 400);
        }

        $work = Work::with('parameters')->findOrFail($workId);

        // Get amounts from parameters
        $amount = $work->getParameter(Work::AMOUNT) ?? 0;
        $vat = $work->getParameter(Work::VAT) ?? 0;
        $illegalAmount = $work->getParameter(Work::ILLEGALAMOUNT) ?? 0;

        // Set payment dates
        $paidAt = $paidDate ? Carbon::parse($paidDate) : now();
        $vatDateValue = $vatDate ? Carbon::parse($vatDate) : $paidAt;

        // Update work dates
        $work->paid_at = $paidAt;
        $work->vat_date = $vatDateValue;
        $work->save();

        // Get payment date from request
        $paymentDate = $paidDate ? Carbon::parse($paidDate)->format('Y-m-d') : null;

        // Update payment parameters using service (reads old value, updates, creates income if delta > 0)
        $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $amount, $paymentDate);
        $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $vat, $paymentDate);
        $this->incomeService->updateParameterAndCreateIncome($work, Work::ILLEGALPAID, $illegalAmount, $paymentDate);

        return response()->json(['success' => true]);
    }

    /**
     * Update invoice parameter value
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvoiceParameter(Request $request)
    {
        try {
            $workId = $request->input('work_id');
            $parameterId = $request->input('parameter_id');
            $value = $request->input('value');

            if (!$workId || !$parameterId) {
                return response()->json(['error' => 'Work ID and Parameter ID are required'], 400);
            }

            // Validate parameter IDs - only allow the 6 editable parameters
            $allowedParameters = [
                Work::AMOUNT,
                Work::PAID,
                Work::VAT,
                Work::VATPAYMENT,
                Work::ILLEGALAMOUNT,
                Work::ILLEGALPAID,
            ];

            if (!in_array((int)$parameterId, $allowedParameters)) {
                return response()->json(['error' => 'Invalid parameter ID'], 400);
            }

            // Validate value is numeric
            if (!is_numeric($value)) {
                return response()->json(['error' => 'Value must be numeric'], 400);
            }

            $work = Work::findOrFail($workId);

            // For payment parameters: use service to update work_parameter and create income
            if (in_array((int)$parameterId, [Work::PAID, Work::VATPAYMENT, Work::ILLEGALPAID])) {
                // Get payment date from request if provided
                $paymentDate = $request->input('payment_date');
                
                // Service will: read old value, update work_parameter, calculate delta, create income if delta > 0
                $this->incomeService->updateParameterAndCreateIncome(
                    $work, 
                    (int)$parameterId, 
                    (float)$value, 
                    $paymentDate
                );
            } else {
                // For non-payment parameters: use standard update method
                $work->setParameterValue((int)$parameterId, (float)$value);
            }

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'value' => number_format((float)$value, 2)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in updateInvoiceParameter: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice date field (paid_at or vat_date)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvoiceDate(Request $request)
    {
        try {
            $workId = $request->input('work_id');
            $field = $request->input('field');
            $value = $request->input('value');

            if (!$workId || !$field) {
                return response()->json(['error' => 'Work ID and field name are required'], 400);
            }

            // Validate field names - only allow paid_at and vat_date
            $allowedFields = ['paid_at', 'vat_date'];

            if (!in_array($field, $allowedFields)) {
                return response()->json(['error' => 'Invalid field name'], 400);
            }

            $work = Work::findOrFail($workId);
            
            // Store old value to check if it changed
            $oldValue = $work->$field;
            
            // Update the date field
            if ($value) {
                $work->$field = Carbon::parse($value);
            } else {
                $work->$field = null;
            }
            
            $work->save();
            $work->refresh();
            
            // Check if date was set/changed and automatically fill payment amounts
            $dateChanged = false;
            if ($work->$field) {
                if ($oldValue === null) {
                    $dateChanged = true; // Was null, now set
                } else {
                    // Compare dates
                    $oldDateStr = $oldValue instanceof Carbon ? $oldValue->format('Y-m-d') : Carbon::parse($oldValue)->format('Y-m-d');
                    $newDateStr = $work->$field instanceof Carbon ? $work->$field->format('Y-m-d') : Carbon::parse($work->$field)->format('Y-m-d');
                    $dateChanged = ($oldDateStr !== $newDateStr);
                }
            }
            
            // If paid_at date was set/changed, automatically fill PAID with AMOUNT (if amount > 0)
            if ($dateChanged && $field === 'paid_at' && $work->paid_at) {
                $amount = $work->getParameter(Work::AMOUNT) ?? 0;
                $currentPaid = $work->getParameter(Work::PAID) ?? 0;
                
                // Only update if amount > 0 and paid is less than amount
                if ($amount > 0 && $currentPaid < $amount) {
                    $paymentDate = $work->paid_at instanceof Carbon ? $work->paid_at->format('Y-m-d') : Carbon::parse($work->paid_at)->format('Y-m-d');
                    $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $amount, $paymentDate);
                }
            }
            
            // If vat_date was set/changed, automatically fill VATPAYMENT with VAT (if vat > 0)
            if ($dateChanged && $field === 'vat_date' && $work->vat_date) {
                $vat = $work->getParameter(Work::VAT) ?? 0;
                $currentVatPaid = $work->getParameter(Work::VATPAYMENT) ?? 0;
                
                // Only update if vat > 0 and vatPayment is less than vat
                if ($vat > 0 && $currentVatPaid < $vat) {
                    $paymentDate = $work->vat_date instanceof Carbon ? $work->vat_date->format('Y-m-d') : Carbon::parse($work->vat_date)->format('Y-m-d');
                    $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $vat, $paymentDate);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'value' => $work->$field ? $work->$field->format('Y-m-d') : '-'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in updateInvoiceDate: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply unified payment to all works with the same invoice code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyUnifiedPayment(Request $request)
    {
        try {
            $invoiceCode = $request->input('invoice');
            $paymentType = $request->input('paymentType');
            $mainPaymentDate = $request->input('mainPaymentDate');
            $vatPaymentDate = $request->input('vatPaymentDate');
            $partialMain = (float)($request->input('partialMain') ?? 0);
            $partialVat = (float)($request->input('partialVat') ?? 0);

            if (!$invoiceCode || !$paymentType) {
                return response()->json(['error' => 'Invoice code and payment type are required'], 400);
            }
            
            // Validate payment dates are provided
            if (!$mainPaymentDate || trim($mainPaymentDate) === '') {
                return response()->json(['error' => 'Əsas ödəniş tarixi daxil edilməlidir.'], 422);
            }
            
            if (!$vatPaymentDate || trim($vatPaymentDate) === '') {
                return response()->json(['error' => 'ƏDV ödəniş tarixi daxil edilməlidir.'], 422);
            }
            
            // Validate and parse date formats
            try {
                $mainPaymentDateCarbon = Carbon::createFromFormat('Y-m-d', trim($mainPaymentDate));
            } catch (\Exception $e) {
                return response()->json(['error' => 'Yanlış əsas ödəniş tarixi formatı. Gözlənilən format: YYYY-MM-DD.'], 422);
            }
            
            try {
                $vatPaymentDateCarbon = Carbon::createFromFormat('Y-m-d', trim($vatPaymentDate));
            } catch (\Exception $e) {
                return response()->json(['error' => 'Yanlış ƏDV ödəniş tarixi formatı. Gözlənilən format: YYYY-MM-DD.'], 422);
            }

            // Fetch all works with the same invoice code (load service for company_id)
            $works = Work::with('parameters', 'service')
                ->where('code', $invoiceCode)
                ->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'No works found for this invoice'], 404);
            }

            // Payment date is already validated and parsed above
            DB::beginTransaction();

            if ($paymentType === 'full') {
                foreach ($works as $work) {
                    try {
                        $amount = $work->getParameter(Work::AMOUNT) ?? 0;
                        $vat = $work->getParameter(Work::VAT) ?? 0;
                        $illegalAmount = $work->getParameter(Work::ILLEGALAMOUNT) ?? 0;
                        $currentPaid = $work->getParameter(Work::PAID) ?? 0;
                        $currentVatPaid = $work->getParameter(Work::VATPAYMENT) ?? 0;
                        $currentIllegalPaid = $work->getParameter(Work::ILLEGALPAID) ?? 0;

                        // Update date fields - use separate dates
                        $work->paid_at = $mainPaymentDateCarbon;
                        $work->vat_date = $vatPaymentDateCarbon;
                        $work->save();
                        $work->refresh();

                        // Calculate deltas for transaction creation
                        $paidDelta = max(0, $amount - $currentPaid);
                        $vatDelta = max(0, $vat - $currentVatPaid);
                        $illegalDelta = max(0, $illegalAmount - $currentIllegalPaid);

                        // Payment dates for transactions
                        $mainPaymentDateStr = $mainPaymentDateCarbon->format('Y-m-d');
                        $vatPaymentDateStr = $vatPaymentDateCarbon->format('Y-m-d');

                        // Base payment - use main payment date
                        if ($paidDelta > 0) {
                            \Log::info('Processing base payment', [
                                'work_id' => $work->id,
                                'currentPaid' => $currentPaid,
                                'targetAmount' => $amount,
                                'delta' => $paidDelta
                            ]);
                            // Use service to update parameter and create transaction
                            $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $amount, $mainPaymentDateStr);
                        } elseif ($amount > 0) {
                            // Even if delta is 0, ensure parameter is set to correct value
                            $work->setParameterValue(Work::PAID, $amount);
                        }

                        // VAT payment (only process if VAT exists) - use VAT payment date
                        if ($vatDelta > 0 && $vat > 0) {
                            \Log::info('Processing VAT payment', [
                                'work_id' => $work->id,
                                'currentVatPaid' => $currentVatPaid,
                                'targetVat' => $vat,
                                'delta' => $vatDelta
                            ]);
                            $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $vat, $vatPaymentDateStr);
                        } elseif ($vat > 0) {
                            // Even if delta is 0, ensure parameter is set to correct value
                            $work->setParameterValue(Work::VATPAYMENT, $vat);
                        }

                        // Illegal amount payment (only process if illegalAmount exists) - use main payment date
                        if ($illegalDelta > 0 && $illegalAmount > 0) {
                            \Log::info('Processing illegal amount payment', [
                                'work_id' => $work->id,
                                'currentIllegalPaid' => $currentIllegalPaid,
                                'targetIllegalAmount' => $illegalAmount,
                                'delta' => $illegalDelta
                            ]);
                            $this->incomeService->updateParameterAndCreateIncome($work, Work::ILLEGALPAID, $illegalAmount, $mainPaymentDateStr);
                        } elseif ($illegalAmount > 0) {
                            // Even if delta is 0, ensure parameter is set to correct value
                            $work->setParameterValue(Work::ILLEGALPAID, $illegalAmount);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error processing work payment in applyUnifiedPayment (full)', [
                            'work_id' => $work->id,
                            'invoice_code' => $invoiceCode,
                            'error' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e; // Re-throw to trigger rollback
                    }
                }
            } else {
                // Partial payment: distribute amounts sequentially
                $remainingMain = max(0, (float)$partialMain);
                $remainingVat = max(0, (float)$partialVat);

                foreach ($works as $work) {
                    try {
                        $amount = $work->getParameter(Work::AMOUNT) ?? 0;
                        $vat = $work->getParameter(Work::VAT) ?? 0;
                        $currentPaid = $work->getParameter(Work::PAID) ?? 0;
                        $currentVatPaid = $work->getParameter(Work::VATPAYMENT) ?? 0;

                        // Calculate remaining amounts for this work
                        $mainRemaining = max(0, $amount - $currentPaid);
                        $vatRemaining = max(0, $vat - $currentVatPaid);

                        // Payment dates for transactions
                        $mainPaymentDateStr = $mainPaymentDateCarbon->format('Y-m-d');
                        $vatPaymentDateStr = $vatPaymentDateCarbon->format('Y-m-d');

                        // Determine if we'll make any payment
                        $willPayMain = ($remainingMain > 0 && $mainRemaining > 0);
                        $willPayVat = ($remainingVat > 0 && $vatRemaining > 0 && $vat > 0);
                        
                        // Update date fields if payment will be made - use separate dates
                        if ($willPayMain) {
                            $work->paid_at = $mainPaymentDateCarbon;
                        }
                        if ($willPayVat) {
                            $work->vat_date = $vatPaymentDateCarbon;
                        }
                        if ($willPayMain || $willPayVat) {
                            $work->save();
                            $work->refresh();
                        }

                        // Distribute main amount - use main payment date
                        if ($remainingMain > 0 && $mainRemaining > 0) {
                            $toPayMain = min($remainingMain, $mainRemaining);
                            $newPaid = $currentPaid + $toPayMain;
                            
                            // Calculate delta for this payment
                            $paidDelta = $newPaid - $currentPaid;
                            
                            \Log::info('Processing partial base payment', [
                                'work_id' => $work->id,
                                'currentPaid' => $currentPaid,
                                'toPayMain' => $toPayMain,
                                'newPaid' => $newPaid,
                                'delta' => $paidDelta
                            ]);
                            
                            // Use service to update parameter and create transaction
                            $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, $newPaid, $mainPaymentDateStr);
                            
                            $remainingMain -= $toPayMain;
                        }

                        // Distribute VAT amount - use VAT payment date
                        if ($remainingVat > 0 && $vatRemaining > 0 && $vat > 0) {
                            $toPayVat = min($remainingVat, $vatRemaining);
                            $newVatPaid = $currentVatPaid + $toPayVat;
                            
                            // Calculate delta for this payment
                            $vatDelta = $newVatPaid - $currentVatPaid;
                            
                            \Log::info('Processing partial VAT payment', [
                                'work_id' => $work->id,
                                'currentVatPaid' => $currentVatPaid,
                                'toPayVat' => $toPayVat,
                                'newVatPaid' => $newVatPaid,
                                'delta' => $vatDelta
                            ]);
                            
                            // Use service to update parameter and create transaction
                            $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, $newVatPaid, $vatPaymentDateStr);
                            
                            $remainingVat -= $toPayVat;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error processing work payment in applyUnifiedPayment (partial)', [
                            'work_id' => $work->id,
                            'invoice_code' => $invoiceCode,
                            'error' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e; // Re-throw to trigger rollback
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment applied successfully to all tasks',
                'affected_works' => $works->count()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in applyUnifiedPayment', [
                'invoice_code' => $invoiceCode ?? 'unknown',
                'payment_type' => $paymentType ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Server error occurred while processing payment',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred. Please check the logs.',
                'details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null
            ], 500);
        }
    }

    /**
     * Clear all payments for all works with the same invoice code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearAllPayments(Request $request)
    {
        try {
            $invoiceCode = $request->input('invoice');

            if (!$invoiceCode) {
                return response()->json(['error' => 'Invoice code is required'], 400);
            }

            // Fetch all works with the same invoice code
            $works = Work::with('parameters')
                ->where('code', $invoiceCode)
                ->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'No works found for this invoice'], 404);
            }

            DB::beginTransaction();

            foreach ($works as $work) {
                // Use WorkIncomeService to clear payments - this will also delete transactions
                // When setting to 0, the service will delete the transactions
                $this->incomeService->updateParameterAndCreateIncome($work, Work::PAID, 0, null);
                $this->incomeService->updateParameterAndCreateIncome($work, Work::VATPAYMENT, 0, null);
                $this->incomeService->updateParameterAndCreateIncome($work, Work::ILLEGALPAID, 0, null);

                // Clear all date fields
                $work->paid_at = null;
                $work->vat_date = null;
                $work->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All payments cleared successfully',
                'affected_works' => $works->count()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in clearAllPayments: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear payment dates for works with the same invoice code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearInvoiceDates(Request $request)
    {
        try {
            $invoiceCode = $request->input('invoice');
            $dateType = $request->input('date_type'); // 'main', 'vat', or null (both)

            if (!$invoiceCode) {
                return response()->json(['error' => 'Invoice code is required'], 400);
            }

            // Fetch all works with the same invoice code
            $works = Work::where('code', $invoiceCode)->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'No works found for this invoice'], 404);
            }

            DB::beginTransaction();

            $message = '';
            foreach ($works as $work) {
                // Clear date fields based on date_type
                if ($dateType === 'main') {
                    $work->paid_at = null;
                    $message = 'Əsas ödəniş tarixləri təmizləndi';
                } elseif ($dateType === 'vat') {
                    $work->vat_date = null;
                    $message = 'ƏDV ödəniş tarixləri təmizləndi';
                } else {
                    // Clear both if no specific type is provided
                    $work->paid_at = null;
                    $work->vat_date = null;
                    $message = 'Ödəniş tarixləri təmizləndi';
                }
                $work->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_works' => $works->count()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in clearInvoiceDates: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign bulk invoice code to multiple works
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignBulkInvoiceCode(Request $request)
    {
        try {
            $invoiceCode = $request->input('invoice_code');
            $invoiceDate = $request->input('invoice_date');
            $workIds = $request->input('work_ids', []);

            if (!$invoiceCode || empty(trim($invoiceCode))) {
                return response()->json(['error' => 'Qaimə nömrəsi daxil edilməlidir'], 400);
            }

            if (empty($workIds) || !is_array($workIds)) {
                return response()->json(['error' => 'İş ID-ləri daxil edilməlidir'], 400);
            }

            // Fetch all works
            $works = Work::whereIn('id', $workIds)->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'Heç bir iş tapılmadı'], 404);
            }

            DB::beginTransaction();

            $affectedCount = 0;
            foreach ($works as $work) {
                // Only assign if work doesn't already have an invoice code
                if (empty($work->code)) {
                    $work->code = trim($invoiceCode);
                    // Set invoice date if provided
                    if ($invoiceDate) {
                        try {
                            $work->invoiced_date = Carbon::parse($invoiceDate);
                        } catch (\Exception $e) {
                            \Log::warning('Invalid invoice date format: ' . $invoiceDate);
                        }
                    }

                    // Auto-set invoice company from related AsanImza company if not already set
                    if (empty($work->invoice_company_id) && $work->asanImza && $work->asanImza->company_id) {
                        $work->invoice_company_id = $work->asanImza->company_id;
                    }

                    $work->save();
                    $affectedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Qaimə nömrəsi və tarixi uğurla təyin edildi',
                'affected_works' => $affectedCount,
                'invoice_code' => trim($invoiceCode)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in assignBulkInvoiceCode: ' . $e->getMessage());
            return response()->json([
                'error' => 'Xəta baş verdi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update bulk invoice code and date for multiple works
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBulkInvoiceCode(Request $request)
    {
        try {
            $oldInvoiceCode = $request->input('old_invoice_code');
            $invoiceCode = $request->input('invoice_code');
            $invoiceDate = $request->input('invoice_date');
            $workIds = $request->input('work_ids', []);

            if (!$oldInvoiceCode || empty(trim($oldInvoiceCode))) {
                return response()->json(['error' => 'Köhnə qaimə nömrəsi daxil edilməlidir'], 400);
            }

            if (!$invoiceCode || empty(trim($invoiceCode))) {
                return response()->json(['error' => 'Yeni qaimə nömrəsi daxil edilməlidir'], 400);
            }

            if (empty($workIds) || !is_array($workIds)) {
                return response()->json(['error' => 'İş ID-ləri daxil edilməlidir'], 400);
            }

            // Fetch all works with the old invoice code
            $works = Work::where('code', $oldInvoiceCode)
                ->whereIn('id', $workIds)
                ->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'Heç bir iş tapılmadı'], 404);
            }

            DB::beginTransaction();

            $affectedCount = 0;
            foreach ($works as $work) {
                $work->code = trim($invoiceCode);
                // Update invoice date if provided
                if ($invoiceDate) {
                    try {
                        $work->invoiced_date = Carbon::parse($invoiceDate);
                    } catch (\Exception $e) {
                        \Log::warning('Invalid invoice date format: ' . $invoiceDate);
                    }
                }

                // If invoice company is not set, auto-fill from AsanImza company
                if (empty($work->invoice_company_id) && $work->asanImza && $work->asanImza->company_id) {
                    $work->invoice_company_id = $work->asanImza->company_id;
                }

                $work->save();
                $affectedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Qaimə məlumatları uğurla yeniləndi',
                'affected_works' => $affectedCount,
                'invoice_code' => trim($invoiceCode)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in updateBulkInvoiceCode: ' . $e->getMessage());
            return response()->json([
                'error' => 'Xəta baş verdi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice company for works (by invoice code or IDs)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvoiceCompany(Request $request)
    {
        $request->validate([
            'company_id'   => ['required', 'integer', 'exists:companies,id'],
            'invoice_code' => ['nullable', 'string'],
            'ids'          => ['nullable', 'array'],
            'ids.*'        => ['integer'],
        ]);

        $companyId = (int) $request->input('company_id');
        $invoiceCode = $request->input('invoice_code');
        $ids = $request->input('ids', []);

        if (!$invoiceCode && empty($ids)) {
            return response()->json(['error' => 'Heç bir iş seçilməyib'], 400);
        }

        $query = Work::query();

        if ($invoiceCode) {
            $query->where('code', $invoiceCode);
        } elseif (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $affected = $query->update(['invoice_company_id' => $companyId]);

        return response()->json([
            'success'        => true,
            'affected_works' => $affected,
        ]);
    }

    /**
     * Remove bulk invoice code and date from multiple works
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBulkInvoiceCode(Request $request)
    {
        try {
            $invoiceCode = $request->input('invoice_code');
            $workIds = $request->input('work_ids', []);

            if (!$invoiceCode || empty(trim($invoiceCode))) {
                return response()->json(['error' => 'Qaimə nömrəsi daxil edilməlidir'], 400);
            }

            if (empty($workIds) || !is_array($workIds)) {
                return response()->json(['error' => 'İş ID-ləri daxil edilməlidir'], 400);
            }

            // Fetch all works with the invoice code
            $works = Work::where('code', $invoiceCode)
                ->whereIn('id', $workIds)
                ->get();

            if ($works->isEmpty()) {
                return response()->json(['error' => 'Heç bir iş tapılmadı'], 404);
            }

            DB::beginTransaction();

            $affectedCount = 0;
            foreach ($works as $work) {
                $work->code = null;
                $work->invoiced_date = null;
                $work->save();
                $affectedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Qaimə nömrəsi və tarixi uğurla silindi',
                'affected_works' => $affectedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in removeBulkInvoiceCode: ' . $e->getMessage());
            return response()->json([
                'error' => 'Xəta baş verdi',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
