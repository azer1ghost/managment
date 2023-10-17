<?php

namespace App\Http\Controllers\Modules;

use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\Company;
use App\Models\CustomerEngagement;
use App\Models\Department;
use App\Models\Referral;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->middleware('auth');
        $this->authorizeResource(Client::class, 'client');
        $this->clientRepository = $clientRepository;
    }

    public function search(Request $request): object
    {
        $clients = Client::with('coordinators')->where('fullname', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('voen', 'LIKE', "%{$request->get('search')}%")
            ->limit(10)
            ->get(['id', 'fullname', 'voen', 'active']);

        $clientsArray = [];

        foreach ($clients as $client) {
            $clientsArray[] = [
                "id"   => $client->id,
                "text" => "{$client->fullname_with_voen}",
            ];
        }

        return (object) [
            'results' => $clientsArray,
            'pagination' => [
                "more" => false
            ]
        ];
    }

    public function export(Request $request)
    {
//        $filters = json_decode($request->get('filters'), true);
//
//        return  (new ClientsExport($this->clientRepository, $filters))->download('clients.xlsx');

        return \Excel::download(new ClientsExport(), 'clients.xlsx');
    }

    public function index(Request $request)
    {

        if($request->has('created_at')){
            $createdTime = $request->get('created_at');
        }else{
            $createdTime = now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');
        }

        $filters = [
            'satisfaction' => $request->get('satisfaction'),
            'search' => $request->get('search'),
            'type' => $request->get('type'),
            'active' => $request->get('active'),
            'limit' => $request->get('limit',25),
            'coordinator' => $request->get('coordinator'),
            'free_clients' => $request->has('free_clients'),
            'check-created_at' => $request->has('check-created_at'),
            'created_at' => $createdTime,
            'company' => $request->get('company_id'),
            'free_company' => $request->has('free_company'),
            'free_coordinator' => $request->has('free_coordinator'),
            'users' => $request->get('users'),
            'reference' => User::get(['id', 'name', 'surname']),
        ];
        $clients = $this->clientRepository->allFilteredClients($filters)->latest();
        if (auth()->id() == 172) {
            $clients->where('user_id', auth()->id());
        }

        if(is_numeric($filters['limit'])) {
            $clients = $clients->paginate($filters['limit']);
        }else {
            $clients = $clients->get();
        }
        $services = Service::get(['id', 'name', 'detail']);

        return view('pages.clients.index')
            ->with([
                'filters' => $filters,
                'types' => [
                    (string) 'none' => trans('translates.general.typeChoose'),
                    (string) Client::LEGAL => trans('translates.general.legal'),
                    (string) Client::PHYSICAL => trans('translates.general.physical'),
                    (string) Client::FOREIGNPHYSICAL => trans('translates.general.foreignphysical'),
                    (string) Client::FOREIGNLEGAL => trans('translates.general.foreignlegal'),
                ],
                'actives' => [
                    (string) 'none' => trans('translates.general.activeChoose'),
                    (string) Client::ACTIVE => trans('translates.buttons.active'),
                    (string) Client::PASSIVE => trans('translates.buttons.passive')
                ],
                'clients' => $clients,
                'coordinators' => User::isActive()->where('department_id', Department::COORDINATOR)->get(['id', 'name', 'surname']),
                'companies' => Company::get(['id','name', 'logo']),
                'satisfactions' => Client::satisfactions(),
                'users' => User::isActive()->get(['id', 'name', 'surname']),
                'services' => $services
            ]);
    }

    public function create()
    {
        return view('pages.clients.edit')
            ->with([
                'action' => route('clients.store'),
                'method' => 'POST',
                'data' => new Client(),
                'satisfactions' => Client::satisfactions(),
                'channels' => Client::channels(),
                'companies' => Company::get(['id','name']),
                'users' => User::isActive()->get(),
                'engagement' => new CustomerEngagement()
            ]);
    }

    public function store(ClientRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['send_sms'] = $request->has('send_sms');
        $validated['active'] = $request->has('active');

        if ($request->file('protocol')) {
            $protocol = $request->file('protocol');
            $document_type = $request->get('fullname').'-'.time(). '.' .$protocol->getClientOriginalExtension();
            $validated['document_type'] = $document_type;
            $validated['protocol'] = $protocol->storeAs('protocol', $document_type);
        }
        $client = Client::create($validated);
        $client->companies()->sync($request->get('companies'));

        if ($request->get('reference_id') !== null){
            $customerEngagement = new CustomerEngagement;
            $customerEngagement->client_id = $client->id;
            $customerEngagement->user_id = $request->get('reference_id');
            $customerEngagement->save();
        }

        if(auth()->user()->hasPermission('viewAny-client')){
            if(is_numeric($client->getAttribute('client_id'))){
                return redirect()
                    ->route('clients.edit', Client::find($validated['client_id']))
                    ->withNotify('success', $client->getAttribute('fullname'));
            }else {
                return redirect()
                    ->route('clients.index')
                    ->withNotify('success', $client->getAttribute('fullname'));
            }
        }
        return redirect()->route('close');
    }

    public function show(Client $client)
    {

        if ($client->services()->where('client_id', $client->id)->first())
        {
            $services = $client->getRelationValue('services');
        }
        else
        {
            $services = Service::get(['id', 'name']);
        }

        $engagement = CustomerEngagement::where('client_id', $client->id)->first();

        return view('pages.clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $client,
                'satisfactions' => Client::satisfactions(),
                'channels' => Client::channels(),
                'companies' => Company::get(['id','name','logo']),
                'users' => User::get(['id', 'name', 'surname']),
                'engagement' => $engagement,
                'services' => $services,
            ]);
    }

    public function edit(Client $client)
    {

        if ($client->services()->where('client_id', $client->id)->first())
        {
            $services = $client->getRelationValue('services');
        }
        else
        {
            $services = Service::get(['id', 'name']);
        }

        return view('pages.clients.edit')
            ->with([
                'action' => route('clients.update', $client),
                'method' => "PUT",
                'data' => $client,
                'satisfactions' => Client::satisfactions(),
                'channels' => Client::channels(),
                'companies' => Company::get(['id','name', 'logo']),
                'users' => User::get(['id', 'name', 'surname']),
                'engagement' => CustomerEngagement::where('client_id', $client->id)->first(),
                'services' => $services,
            ]);
    }

    public function update(ClientRequest $request, Client $client)
    {

        $services = $request->get('services');

        foreach ($services as $service_id => $data) {
            $client = Client::find($data['client_id']);

            $pivotData = $client->services()
                ->where('client_service.service_id', $service_id)
                ->first();

            if ($pivotData) {
                $client->services()->updateExistingPivot($service_id, ['amount' => $data['amount']]);
            } else {
                $client->services()->attach($service_id, ['amount' => $data['amount']]);
            }
        }

        $validated = $request->validated();
        $validated['send_sms'] = $request->has('send_sms');
        $validated['active'] = $request->has('active');

        if ($request->file('protocol')) {
            $protocol = $request->file('protocol');
            $document_type = $request->get('fullname').'-'.time(). '.' .$protocol->getClientOriginalExtension();
            $validated['document_type'] = $document_type;
            $validated['protocol'] = $protocol->storeAs('protocol', $document_type);

            if (Storage::exists($client->getAttribute('protocol'))) {
                Storage::delete($client->getAttribute('protocol'));
            }
        }
        $client->update($validated);
        if ($request->get('reference_id')) {
            $customerEngagement = CustomerEngagement::where('client_id', $client->id)->first();
            if ($customerEngagement !== null) {
                $customerEngagement->setAttribute('user_id', $request->get('reference_id'));
                $customerEngagement->save();
            } elseif ($customerEngagement == null) {
                $customerEngagement = new CustomerEngagement;
                $customerEngagement->client_id = $client->id;
                $customerEngagement->user_id = $request->get('reference_id');
                $customerEngagement->save();
            }
        }
        $client->companies()->sync($request->get('companies'));
        return back()->withNotify('info', $client->getAttribute('name'));
    }

    public function sumAssignCoordinators(Request $request)
    {
        $err = 0;
        foreach (explode(',', $request->get('clients')) as $client) {
            if(!Client::find($client)->coordinators()->sync($request->get('users'))){
                $err = 400;
            }
        }
        if ($err == 400) {
            return response()->setStatusCode('204');
        }
        return response('OK');
    }
    public function sumAssignCompanies(Request $request)
    {
        $err = 0;
        foreach (explode(',', $request->get('clients')) as $client) {
            if(!Client::find($client)->companies()->sync($request->get('companies'))){
                $err = 400;
            }
        }

        if ($err == 400) {
            return response()->setStatusCode('204');
        }

        return response('OK');
    }

    public function destroy(Client $client)
    {
        if ($client->delete()) {
            if (Storage::exists($client->getAttribute('protocol'))) {
                Storage::delete($client->getAttribute('protocol'));
        }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function download(Client $client)
    {
        $document_type = $client->getAttribute('document_type');

        return Storage::download('protocol/'.$document_type);
    }
}