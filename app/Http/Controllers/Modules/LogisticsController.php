<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogisticsController extends Controller
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

        return  (new WorksExport($this->workRepository, $filters, $dateFilters))->download('works.xlsx');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $limit  = $request->get('limit', 25);
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
            'paid_at' => $request->get('paid_at'),
            'vat_date' => $request->get('vat_date'),
            'entry_date' => $request->get('entry_date') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
            'invoiced_date' => $request->get('invoiced_date') ?? $startOfMonth . ' - ' . $endOfMonth,
        ];

        if(Work::userCanViewAll() || Work::userCanViewDepartmentWorks()){
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
//            'paid_at_date' => $request->has('check-paid_at'),
            'entry_date' => $request->has('check-entry_date'),
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
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::isActive()->orderBy('ordering')->get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')){
            $works = $works->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }

        $works = $works->whereIn('status', [3,4,5,6,7])->paginate($limit);

        return view('pages.works.index',
            compact('works', 'services', 'departments','users',
                'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters', 'paymentMethods')
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
            'departments' => Department::isActive()->get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function store(WorkRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $work = Work::create($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

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
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
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
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {
        $validated = $request->validated();

        $work->update($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }
        $work->parameters()->sync($parameters);

        return redirect()
            ->route('works.show', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }


    public function paid(Work $work)
    {
        if ($work->update(['paid_at' => now()])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }


    public function destroy(Work $work)
    {
        if ($work->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }


}
