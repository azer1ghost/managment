<?php

namespace App\Http\Controllers\Modules;

use App\Events\WorkChanged;
use App\Events\WorkCreated;
use App\Events\WorkStatusRejected;
use App\Exports\WorksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Interfaces\WorkRepositoryInterface;
use App\Notifications\NotifyClientDirectorSms;
use App\Notifications\NotifyClientSms;
use Carbon\Carbon;
use App\Models\{AsanImza, Company, Department, Service, User, Work, Client};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

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
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date'),
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

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

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters)->whereNotIn('status', [1, 2]);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')) {
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }

        if ($request->has('check-returned_at')) {
            $works = $works->whereNotNull('returned_at');
        }

        $works = $works->paginate($limit);

        return view('pages.works.index',
            compact('works', 'services', 'departments', 'users',
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
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 3, 4, 5, 6, 7],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'entry_date' => $request->has('check-entry_date'),
            'invoiced_date' => $request->has('check-invoiced_date')
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

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
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'statuses' => [1, 2, 3, 5, 7],
        ];

        if (Work::userCanViewAll() || Work::userCanViewDepartmentWorks()) {
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'entry_date' => $request->has('check-entry_date'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
            'invoiced_date' => $request->has('check-invoiced_date')
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

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

        $works = $works->whereIn('status', [4, 6])->paginate($limit);

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
            'entry_date' => $request->has('check-entry_date'),
            'invoiced_date' => $request->has('check-invoiced_date')
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

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

    public function create()
    {
        return view('pages.works.edit')->with([
            'action' => route('works.store'),
            'method' => 'POST',
            'data' => null,
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
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);
        if (in_array($request->get('service_id'), [5, 6, 31, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41])) {
            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)]);
            $work->parameters()->updateExistingPivot($work::VAT, ['value' => (Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)) * 0.18]);
        }
        else if(in_array($request->get('service_id'), [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
            if ($work->getRelationValue('client')->getAttribute('main_paper') > 0) {
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))]);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
            }else
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))]);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
        }
        else if(in_array($request->get('service_id'), [2])) {
            if ($work->getRelationValue('client')->getAttribute('qibmain_paper') > 0) {
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))]);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
            } else
                $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))]);
                $work->parameters()->updateExistingPivot($work::VAT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
        }

        event(new WorkCreated($work));

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
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'destinations' => Work::destinations(),
            'departments' => Department::get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {
//        dd(User::wherePermissions('viewAny-financeClient')->get())
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
            if (!empty($client->getAttribute('phone1'))) {
                (new NotifyClientSms($message))->toSms($client)->send();
            }
            if (!empty($client->getAttribute('phone2'))) {
                (new NotifyClientDirectorSms($message))->toSms($client)->send();
            }
        }

        $validated = $request->validated();
        $validated['datetime'] = $request->get('status') == $work::DONE ? now() : NULL;
        if ($work->getAttribute('injected_at') == null && $request->get('status') == $work::INJECTED) {
            $validated['injected_at'] = now();
        }

        $validated['verified_at'] = $request->has('verified') && !$request->has('rejected') ? now() : NULL;

        if ($work->getAttribute('returned_at') == null && ($request->get('status') == 5) && !$request->has('rejected')) {
            $validated['returned_at'] = now();
        }
        if ($work->getAttribute('entry_date') == null && in_array($request->get('status'), [3, 4, 6]) && !$request->has('rejected')) {
            $validated['entry_date'] = now();
        }
        if (!$request->has('paid_check') && $request->has('rejected') && $request->has('paid_at')) {
            $validated['paid_at'] = null;
        } elseif ($request->has('paid_check') && !$request->has('rejected') && !$request->has('paid_at')) {
            $validated['paid_at'] = now();
        } elseif ($request->has('paid_at')) {
            $validated['paid_at'] = $request->get('paid_at');
        }

        if (!$request->has('vat_paid_check') && $request->has('rejected') && $request->has('vat_date')) {
            $validated['vat_date'] = null;
        } elseif ($request->has('vat_paid_check') && !$request->has('rejected') && !$request->has('vat_date')) {
            $validated['vat_date'] = now();
        } elseif ($request->has('vat_date')) {
            $validated['vat_date'] = $request->get('vat_date');
        }


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
        if (($work->getAttribute('returned_at') || $request->get('returned_at') !== null) && $request->get('parameters')[$work::GB] !== $work->getParameter($work::GB))
        {
            event(new WorkChanged($work));
        }

        $work->update($validated);

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
        if (Work::getClientServiceAmount($work) > 0) {
            if ($firstAsan == 1) {

                if (in_array($request->get('service_id'), [5, 6, 31, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41])) {
                    $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)]);
                }
                else if(in_array($request->get('service_id'), [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])) {
                    if ($work->getRelationValue('client')->getAttribute('main_paper') > 0) {
                        if (in_array($request->get('asan_imza_id'), [29, 34, 36, 40, 30, 32, 33, 41, 43, 39, 46, 47, 49, 50, 48, 22])){
                            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 1.18]);
                        }else{
                            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))]);
                        }
                    }else
                    $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $work->getRelationValue('client')->getAttribute('main_paper')]);
                }
                else if(in_array($request->get('service_id'), [2])) {
                    if ($work->getRelationValue('client')->getAttribute('qibmain_paper') > 0) {
                        if (in_array($request->get('asan_imza_id'), [29, 34, 36, 40, 30, 32, 33, 41, 43, 39, 46, 47, 49, 50, 48, 22])){
                            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 1.18]);
                        }
                        else{
                            $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => (Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))]);
                        }
                    } else
                        $work->parameters()->updateExistingPivot($work::AMOUNT, ['value' => Work::getClientServiceAmount($work) * $work->getParameter($work::GB) + $work->getRelationValue('client')->getAttribute('qibmain_paper')]);
                }


                if (in_array($request->get('asan_imza_id'), [29, 34, 36, 40, 30, 32, 33, 41, 43, 39, 46, 47, 49, 50, 48, 22])) {
                    $work->parameters()->updateExistingPivot($work::VAT, ['value' => 0]);
                } else {
                    if (in_array($request->get('service_id'), [5, 6, 31, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41])) {
                        $work->parameters()->updateExistingPivot($work::VAT, ['value' => (Work::getClientServiceAmount($work) * $work->getParameter($work::SERVICECOUNT)) * 0.18]);
                    } else if (in_array($request->get('service_id'), [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48])){
                        if ($work->getRelationValue('client')->getAttribute('main_paper') > 0){
                            $work->parameters()->updateExistingPivot($work::VAT, ['value' =>  ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('main_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
                        }
                    } else if (in_array($request->get('service_id'), [2])){
                        if ($work->getRelationValue('client')->getAttribute('qibmain_paper') > 0) {
                            $work->parameters()->updateExistingPivot($work::VAT, ['value' =>  ((Work::getClientServiceAmount($work) * ($work->getParameter($work::GB) - $work->getParameter($work::MAINPAGE))) + ($work->getRelationValue('client')->getAttribute('qibmain_paper') * $work->getParameter($work::MAINPAGE))) * 0.18]);
                        }
                    }
                }
            }
        }

        return redirect()
            ->route('works.show', $work)
            ->withNotify('success', $work->getAttribute('name'));
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

    public function editable(Request $request)
    {
        if ($request->ajax()) {
            $work = Work::find($request->pk);
            $work->parameters()->updateExistingPivot($request->name, ['value' => $request->value]);
            return response()->json(['success' => true]);
        }
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
    public function showTotal()
    {
        $dataPoints = [];

        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $works = Work::whereBetween('created_at', [$startMonth, $endMonth])
            ->with('parameters')
            ->get();

        $totalIllegalAmount = $works->sum(function ($work) {
            return $work->getParameter(Work::ILLEGALAMOUNT) ?? 0;
        });

        $totalAmount = $works->sum(function ($work) {
            return $work->getParameter(Work::AMOUNT) ?? 0;
        });

        $totalVat = $works->sum(function ($work) {
            return $work->getParameter(Work::VAT) ?? 0;
        });
        $totalAll = $totalIllegalAmount + $totalAmount + $totalVat;

        $dataPoints[] = [
            "label" => $startMonth->format('Y-m-d'),
            "y" => [
                'Total All' => $totalAll,
                "Illegal Amount" => $totalIllegalAmount,
                "Amount" => $totalAmount,
                "VAT" => $totalVat
            ]
        ];
        return view('pages.works.total', compact('totalIllegalAmount', 'totalAmount', 'totalVat', 'totalAll', 'dataPoints'));
    }
}
