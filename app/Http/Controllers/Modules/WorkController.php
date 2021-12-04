<?php

namespace App\Http\Controllers\Modules;

use App\Events\WorkCreated;
use App\Events\WorkStatusRejected;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use Carbon\Carbon;
use App\Models\{Company, Department, Service, User, Work};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $limit  = $request->get('limit', 25);

        $departmentRequest = Work::userCannotViewAll() ?
            $user->getAttribute('department_id') :
                $request->get('department_id');

        $filters = [
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

        $dateRanges = [
            'datetime' => explode(' - ', $filters['datetime']),
            'created_at' => explode(' - ', $filters['created_at']),
        ];

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
        ];

        $usersQuery = User::has('works')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id']);
        $users = Work::userCannotViewAll() && Work::userCanViewDepartmentWorks() ?
            $usersQuery->where('department_id', $user->getAttribute('department_id'))->get() :
            $usersQuery->get();

        $departments = Department::has('works')->get(['id', 'name']);
        $companies = Company::query()->has('asanImzalar')->limit(10)->get();

        $statuses = Work::statuses();
        $verifies = [1 => trans('translates.columns.unverified'), 2 => trans('translates.columns.verified')];
        $priceVerifies = [1 => trans('translates.columns.price_unverified'), 2 => trans('translates.columns.price_verified')];

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = Work::with('creator', 'department', 'service', 'user', 'client')
            ->when(Work::userCannotViewAll(), function ($query) use ($user){
                if(auth()->user()->hasPermission('viewAllDepartment-work')){
                    $query->where('department_id', $user->getAttribute('department_id'));
                }else{
                    $query
                        ->where(function ($q) use ($user){
                            $q->whereNull('user_id')->where('department_id', $user->getAttribute('department_id'));
                        })
                        ->orWhere('user_id', $user->getAttribute('id'));

                }
            })
            ->where(function($query) use ($filters, $dateRanges, $dateFilters){
                foreach ($filters as $column => $value) {
                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        if($column == 'verified_at'){
                            switch ($value){
                                case 1:
                                    $query->whereNull($column);
                                    break;
                                case 2:
                                    $query->whereNotNull($column);
                                    break;
                            }
                        }
                        else if($column == 'asan_imza_company_id'){
                            $query->whereHas('asanImza', function ($asanImzaQuery) use ($value) {
                                $asanImzaQuery->whereHas('company', function ($companyQuery) use ($value) {
                                    $companyQuery->whereId($value);
                                });
                            });
                        }else{
                            if($column == 'code'){
                                $query->where($column, 'LIKE', "%$value%");
                            }
                            else if (is_numeric($value)){
                                $query->where($column, $value);
                            }
                            else if(is_string($value) && $dateFilters[$column]){
                                $query->whereBetween($column,
                                    [
                                        Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                        Carbon::parse($dateRanges[$column][1])->endOfDay()
                                    ]
                                );
                            }
                        }
                    });
                }
            })
            ->latest('id')
            ->latest('datetime')
            ->paginate($limit);

        return view('panel.pages.works.index',
            compact('works', 'services', 'users', 'departments',
            'filters', 'statuses', 'verifies', 'priceVerifies', 'companies')
        );
    }

    public function create()
    {
        return view('panel.pages.works.edit')->with([
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
        return view('panel.pages.works.edit')->with([
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
        return view('panel.pages.works.edit')->with([
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
}
