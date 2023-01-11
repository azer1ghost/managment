<?php

namespace App\Http\Controllers\Modules;

use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\Company;
use App\Models\Department;
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
        $clients = Client::with('salesUsers')->where('fullname', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('voen', 'LIKE', "%{$request->get('search')}%")
            ->limit(10)
            ->get(['id', 'fullname', 'voen']);

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
        $filters = json_decode($request->get('filters'), true);

        return  (new ClientsExport($this->clientRepository, $filters))->download('clients.xlsx');
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
            'limit' => $request->get('limit',25),
            'salesClient' => $request->get('salesClient'),
            'free_clients' => $request->has('free_clients'),
            'check-created_at' => $request->has('check-created_at'),
            'created_at' => $createdTime,
            'company' => $request->get('company'),
            'free_company' => $request->has('free_company'),
            'users' => $request->get('users')
        ];
        $clients = $this->clientRepository->allFilteredClients($filters)->latest();
        if(is_numeric($filters['limit'])) {
            $clients = $clients->paginate($filters['limit']);
        }else {
            $clients = $clients->get();
        }

        return view('pages.clients.index')
            ->with([
                'filters' => $filters,
                'types' => [
                    (string) 'none' => trans('translates.general.typeChoose'),
                    (string) Client::LEGAL => trans('translates.general.legal'),
                    (string) Client::PHYSICAL => trans('translates.general.physical')
                ],
                'clients' => $clients,
                'salesUsers' => User::isActive()->where('department_id', Department::SALES)->get(['id', 'name', 'surname']),
                'salesClients' => User::isActive()->has('salesClients')->get(['id', 'name', 'surname']),
                'companies' => Company::get(['id','name']),
                'satisfactions' => Client::satisfactions(),
                'users' => User::isActive()->get(['id', 'name', 'surname'])
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
                'companies' => Company::get(['id','name'])
            ]);
    }

    public function store(ClientRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['send_sms'] = $request->has('send_sms');

        if ($request->file('protocol')) {
            $protocol = $request->file('protocol');
            $document_type = $request->get('fullname').'-'.time(). '.' .$protocol->getClientOriginalExtension();
            $validated['document_type'] = $document_type;
            $validated['protocol'] = $protocol->storeAs('protocol', $document_type);
        }
        $client = Client::create($validated);
        $client->companies()->sync($request->get('companies'));

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
        return view('pages.clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $client,
                'satisfactions' => Client::satisfactions(),
                'companies' => Company::get(['id','name'])
            ]);
    }

    public function edit(Client $client)
    {
        return view('pages.clients.edit')
            ->with([
                'action' => route('clients.update', $client),
                'method' => "PUT",
                'data' => $client,
                'satisfactions' => Client::satisfactions(),
                'companies' => Company::get(['id','name'])
            ]);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $validated = $request->validated();
        $validated['send_sms'] = $request->has('send_sms');

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
        $client->companies()->sync($request->get('companies'));
        return back()->withNotify('info', $client->getAttribute('name'));
    }

    public function sumAssignSales(Request $request)
    {
        $err = 0;
        foreach (explode(',', $request->get('clients')) as $client) {
            if(!Client::find($client)->salesUsers()->sync($request->get('users'))){
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