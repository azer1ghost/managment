<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogisticsRequest;
use App\Interfaces\LogisticsRepositoryInterface;
use App\Models\Company;
use App\Models\Logistics;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    protected LogisticsRepositoryInterface $logisticsRepository;

    public function __construct(LogisticsRepositoryInterface $logisticsRepository)
    {
//        $this->middleware('auth');
//        $this->authorizeResource(Logistics::class, 'logistics');
        $this->logisticsRepository = $logisticsRepository;
    }

//    public function export(Request $request)
//    {
//        $filters = json_decode($request->get('filters'), true);
//        $dateFilters = json_decode($request->get('dateFilters'), true);
//
//        return  (new LogisticsExport($this->logisticsRepository, $filters, $dateFilters))->download('logistics.xlsx');
//    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $limit  = $request->get('limit', 25);
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

        $filters = [
            'limit' => $limit,
            'reg_number' => $request->get('reg_number'),
            'service_id' => $request->get('service_id'),
            'reference_id' => $request->get('reference_id'),
            'client_id' => $request->get('client_id'),
            'status' => $request->get('status'),
            'transport_type' => $request->get('transport_type'),
            'user_id' => $request->get('user_id'),
            'paid_at' => $request->get('paid_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'datetime' => $request->get('datetime') ?? $startOfMonth . ' - ' . $endOfMonth,
        ];

        $dateFilters = [
            'datetime' => $request->has('check-datetime'),
            'created_at' => $request->has('check-created_at'),
            'paid_at_date' => $request->has('check-paid_at'),
        ];

        $users = User::has('logistics')->with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id'])->get();
        $references = User::with('position', 'role')->isActive()->select(['id', 'name', 'surname', 'position_id', 'role_id'])->get();

        $statuses = Logistics::statuses();
        $transportTypes = Logistics::transportTypes();

        $services = Service::query()->where('company_id', Company::MOBIL_LOGISTICS)->get(['id', 'name', 'detail']);

        $logistics = $this->logisticsRepository->allFilteredLogistics($filters, $dateFilters);

        $paid_at_explode = explode(' - ', $request->get('paid_at_date'));

        if ($request->has('check-paid_at')){
            $logistics = $logistics->whereBetween('paid_at', [Carbon::parse($paid_at_explode[0])->startOfDay(), Carbon::parse($paid_at_explode[1])->endOfDay()]);
        }

        $logistics = $logistics->paginate($limit);

        return view('pages.logistics.index',
            compact('logistics', 'services', 'users', 'filters', 'statuses', 'transportTypes', 'dateFilters', 'references')
        );
    }

    public function create()
    {
        return view('pages.logistics.edit')->with([
            'action' => route('logistics.store'),
            'method' => 'POST',
            'data' => null,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function store(LogisticsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $service = Service::whereId($request->get('service_id'))->first();
        $name = $service->getAttribute('name');
        $logistics = Logistics::create($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $logistics->parameters()->sync($parameters);
//        $reg_number = substr($name, 0, 3) . '/' .
//            now()->format('d/m') . '/' .
//            $logistics->getAttribute('id') . '/' .
//            substr($name, -1);
//        $logistics->setAttribute('reg_number', $reg_number);
        $logistics->save();

        return redirect()
            ->route('logistics.edit', $logistics)
            ->withNotify('success', $logistics->getAttribute('name'));
    }

    public function show(Logistics $logistic)
    {
        return view('pages.logistics.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $logistic,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function edit(Logistics $logistic)
    {
        return view('pages.logistics.edit')->with([
            'action' => route('logistics.update', $logistic),
            'method' => 'PUT',
            'data' => $logistic,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(LogisticsRequest $request, Logistics $logistic): RedirectResponse
    {
        $validated = $request->validated();
        if (!$request->has('paid_check') && $request->has('paid_at')){
            $validated['paid_at'] = null;
        }
        elseif ($request->has('paid_check') && !$request->has('paid_at')) {
            $validated['paid_at'] = now();
        }
        elseif ($request->has('paid_at')){
            $validated['paid_at'] = $request->get('paid_at');
        }

        if (!$request->has('datetime-check') && $request->has('datetime')){
            $validated['datetime'] = null;
        }
        elseif ($request->has('datetime-check') && !$request->has('datetime')) {
            $validated['datetime'] = now();
        }
        elseif ($request->has('datetime-check')){
            $validated['datetime'] = $request->get('datetime');
        }
        $logistic->update($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }
        $logistic->parameters()->sync($parameters);

        return redirect()
            ->route('logistics.show', $logistic)
            ->withNotify('success', $logistic->getAttribute('name'));
    }
    
    public function paid(Logistics $logistic)
    {
        if ($logistic->update(['paid_at' => now()])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
    
    public function destroy(Logistics $logistic)
    {
        if ($logistic->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
