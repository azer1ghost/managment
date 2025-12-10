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

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\{RedirectResponse, Request};

class WorkController extends Controller
{
    protected WorkRepositoryInterface $workRepository;

    public function __construct(WorkRepositoryInterface $workRepository)
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
        $this->workRepository = $workRepository;
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

        $departmentIds = [11, 12, 13, 7, 29, 22, 30, 24];


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

        $departmentIds = [11, 12, 13, 7, 29, 30, 24];


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

        $departmentIds = [11, 12, 13, 7, 29, 30, 24];


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

        $departmentIds = [11, 12, 13, 7, 29, 30, 24];


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

        $departmentIds = [11, 12, 13, 7, 29, 30, 24];


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

        $departmentIds = [11, 12, 13, 7, 29, 30, 24];


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
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)]);
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => (Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)) * 0.18]);
        } else if (in_array($request->get('service_id'), [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
            if ($work->getRelationValue('client')->getAttribute('main_paper') > 0) {
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))]);
                $value = ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 0.18;
                $roundedValue = round($value, 2);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => $roundedValue]);
            } else
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))]);
            $value = ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 0.18;
            $roundedValue = round($value, 2);
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $roundedValue]);
        } else if (in_array($request->get('service_id'), [2])) {
            if ($work->getRelationValue('client')->getAttribute('qibmain_paper') > 0) {
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))]);
                $value = ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 0.18;
                $roundedValue = round($value, 2);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => $roundedValue]);
            } else
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))]);
            $value = ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 0.18;
            $roundedValue = round($value, 2);
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => $roundedValue]);
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

            // ƏDV olmayan Asan İmzalar
            $noVatAsan = [
                29, 34, 36, 39, 40, 30, 32, 33, 41, 43, 39, 46, 47, 49, 50, 48, 22,
                53, 54, 55, 56, 57, 63, 80, 60, 71, 74, 83, 61, 73, 64, 72, 82, 100,
                102, 98, 95, 94, 93, 91, 90, 87, 83, 78, 127, 128, 129, 130, 63,
                102, 113, 117, 98, 107, 114, 73
            ];

            if (!in_array($asanImzaId, $noVatAsan) && $amount !== null) {
                $vat = round($amount * 0.18, 2);
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
        $work->parameters()->updateExistingPivot($work::PAID, ['value' => $work->getParameter($work::AMOUNT)]);
        return back();
    }

    public function vatPaid(Work $work, Request $request)
    {
        $date = $request->get('vatPaid_at') ?? now();
        $work->update(['vat_date' => $date]);
        $work->parameters()->updateExistingPivot($work::VATPAYMENT, ['value' => $work->getParameter($work::VAT)]);
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

        $noVatAsan = [
            29, 34, 36, 39, 40, 30, 32, 33, 41, 43, 39, 46, 47, 49, 50, 48, 22,
            53, 54, 55, 56, 57, 63, 80, 60, 71, 74, 83, 61, 73, 64, 72, 82, 100,
            102, 98, 95, 94, 93, 91, 90, 87, 83, 78, 127, 128, 129, 130, 63,
            102, 113, 117, 98, 107, 114, 73
        ];

        if (!in_array($asanImzaId, $noVatAsan) && $amount !== null) {
            $vat = round($amount * 0.18, 2);
        }

        $work->parameters()->updateExistingPivot($work::VAT, ['value' => $vat]);

        return [round($amount, 2), round($vat, 2)];
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
        $MobexTotal   = ($AMBGICashTotals['MOBEX'] ?? 0) + ($BBGICashTotals['MOBEX'] ?? 0) + ($HNBGICashTotals['ASAZA'] ?? 0);

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
        $since = now()->subYear()->startOfDay();

        // Subquery: hər iş üçün amount = 33+34+38 parametrlərinin cəmi
        $amountSub = DB::raw("
            (
                SELECT wp.work_id, SUM(CAST(wp.value AS DECIMAL(18,2))) AS amount
                FROM work_parameter wp
                WHERE wp.parameter_id IN (33,34,38)
                GROUP BY wp.work_id
            ) AS amt
        ");

        $rows = DB::table('works as w')
            ->join('asan_imzalar as ai', 'ai.id', '=', 'w.asan_imza_id')
            ->join('companies as c', 'c.id', '=', 'ai.company_id')
            ->join($amountSub, 'amt.work_id', '=', 'w.id')
            ->whereNotNull('w.asan_imza_id')
            ->where('w.created_at', '>=', $since)
            ->groupBy('c.id', 'c.name')
            ->select([
                'c.id as company_id',
                'c.name as company_name',
                DB::raw('SUM(amt.amount) AS total_payment')
            ])
            ->orderByDesc('total_payment')
            ->get();

        return response()->json([
            'since' => $since->toDateString(),
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

            $html = view('works.partials.invoice-popup', compact('works'))->render();

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
        $amount = $work->getParameterValue(Work::AMOUNT) ?? 0;
        $vat = $work->getParameterValue(Work::VAT) ?? 0;
        $illegalAmount = $work->getParameterValue(Work::ILLEGALAMOUNT) ?? 0;

        // Set payment dates
        $paidAt = $paidDate ? Carbon::parse($paidDate) : now();
        $vatDateValue = $vatDate ? Carbon::parse($vatDate) : $paidAt;

        // Update work dates
        $work->paid_at = $paidAt;
        $work->vat_date = $vatDateValue;
        $work->save();

        // Update parameter values
        $work->setParameterValue(Work::PAID, $amount);
        $work->setParameterValue(Work::VATPAYMENT, $vat);
        $work->setParameterValue(Work::ILLEGALPAID, $illegalAmount);

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

            $work = Work::with('parameters')->findOrFail($workId);

            // Update parameter value
            $work->setParameterValue((int)$parameterId, (float)$value);

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
}
