<?php

namespace App\Http\Controllers\Modules;

use App\Events\WorkCreated;
use App\Events\WorkStatusRejected;
use App\Exports\WorksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Interfaces\WorkRepositoryInterface;
use Carbon\Carbon;
use App\Models\{Company, Department, Service, User, Work};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        return  (new WorksExport($this->workRepository, $filters, $dateFilters))->download('works.xlsx');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $limit  = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

        $departmentRequest = Work::userCannotViewAll() ?
            $user->getAttribute('department_id') :
                $request->get('department_id');

        $filters = [
            'limit' => $limit,
            'code' => $request->get('code'),
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
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
        ];

        if(Work::userCanViewAll() || Work::userCanViewDepartmentWorks()){
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'paid_at' => $request->has('check-paid_at'),
            'vat_date' => $request->has('check-vat_paid_at'),
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

        $departments = Department::has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $paymentMethods = Work::paymentMethods();
        $statuses = Work::statuses();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

//        if (!$request->has('check-created_at')){
//            $works = $works->whereBetween('created_at', [Carbon::parse($startOfMonth)->startOfDay(), Carbon::parse($endOfMonth)->endOfDay()]);
//        }

        if(is_numeric($limit)) {
            $works = $works->paginate($limit);
        }else {
            $works = $works->get();
        }

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
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'departments' => Department::get(['id', 'name']),
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
            'users' => User::get(['id', 'name', 'surname']),
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
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {


        $validated = $request->validated();
        $validated['verified_at'] = $request->has('verified') && !$request->has('rejected') ? now() : NULL;

        if (!$request->has('paid_check') && $request->has('rejected') && $request->has('paid_at')){
            $validated['paid_at'] = null;
        }
        elseif ($request->has('paid_check') && !$request->has('rejected') && !$request->has('paid_at')) {
            $validated['paid_at'] = now();
        }
        elseif ($request->has('paid_at')){
            $validated['paid_at'] = $request->get('paid_at');
        }

        if (!$request->has('vat_paid_check') && $request->has('rejected') && $request->has('vat_date')){
            $validated['vat_date'] = null;
        }
        elseif ($request->has('vat_paid_check') && !$request->has('rejected') && !$request->has('vat_date')) {
            $validated['vat_date'] = now();
        }
        elseif ($request->has('vat_date')){
            $validated['vat_date'] = $request->get('vat_date');
        }


        if($work->getAttribute('status') == $work::REJECTED && !$request->has('rejected')){
            $status = $validated['status'] ?? Work::PENDING;
        }else{
            if ($request->has('rejected')){
                $status = Work::REJECTED;
            }else{
                $status = $validated['status'] ?? $work->getAttribute('status');
            }
        }

        $validated['status'] = $status;
        $work->update($validated);

        if($request->has('rejected') && is_numeric($work->getAttribute('user_id'))){
            event(new WorkStatusRejected($work));
        }

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

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

    public function sumVerify(Request $request)
    {
        $err = 0;
        foreach ($request->get('works') ?? [] as $work) {
            if(!Work::find($work)->update(['verified_at' => now()])){
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
                'works' => function ($q) use ($created_at){
                $q ->whereBetween('created_at',
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
                'works as works_verified' => function ($q) use ($created_at){
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
}
