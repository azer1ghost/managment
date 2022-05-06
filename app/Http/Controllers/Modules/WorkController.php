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
            'status' => $request->get('status'),
            'created_at' => $request->get('created_at') ?? now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d'),
            'datetime' => $request->get('datetime') ?? now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d'),
        ];

        if(Work::userCanViewAll() || Work::userCanViewDepartmentWorks()){
            $filters['user_id'] = $request->get('user_id');
        }

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
        ];

        $usersQuery = User::has('works')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users =
// Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
//            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

        $departments = Department::has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $statuses = Work::statuses();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $allDepartments = Department::get(['id', 'name']);

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = $this->workRepository->allFilteredWorks($filters, $dateFilters);

        if(is_numeric($limit)) {
            $works = $works->paginate($limit);
        }else {
            $works = $works->get();
        }

        return view('pages.works.index'
            ,
            compact('works', 'services', 'departments', 'users',
            'filters', 'statuses', 'verifies', 'priceVerifies', 'companies', 'allDepartments', 'dateFilters')
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
        $validated['paid_at'] = $request->has('paid_check') && !$request->has('rejected') ? now() : NULL;
        $validated['vat_date'] = $request->has('vat_paid') && !$request->has('rejected') ? now() : NULL;

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

    public function logs(Work $work)
    {
        return view('pages.works.components.logs')->with([
            'logs' => $work->ledgers()->latest('id')->with('user')->get(),
            'work' => $work
        ]);
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
}
