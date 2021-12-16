<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Client::class, 'client');
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

    public function index(Request $request)
    {
        $search =$request->get('search');
        $limit = $request->get('limit',25);
        $salesClient = $request->get('salesClient');
        $free_clients = $request->has('free_clients');

        return view('panel.pages.clients.index')
            ->with([
                'clients' => Client::with('salesUsers')
                    ->whereNull('client_id')
                    ->when(Client::userCannotViewAll(), function ($query){
                        $query->where(function ($query){
                            $query
                                ->doesnthave('salesUsers')
                                ->orWhereHas('salesUsers', fn($q) => $q->where('id', auth()->id()));
                        });
                    })
                    ->when($free_clients, fn ($query) => $query->doesnthave('salesUsers'))
                    ->when($search, fn ($query) => $query->where('fullname', 'like', "%$search%"))
                    ->when($salesClient, fn ($query) => $query->whereHas('salesUsers', fn($q) => $q->where('id', $salesClient)))
                    ->paginate($limit),
                'salesUsers' => User::where('department_id', 3)->get(['id', 'name', 'surname']),
                'salesClients' => User::has('salesClients')->get(['id', 'name', 'surname'])
            ]);
    }

    public function create()
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => route('clients.store'),
                'method' => 'POST',
                'data' => new Client(),
            ]);
    }

    public function store(ClientRequest $request)
    {
        $validated = $request->validated();
        $client = Client::create($validated);

        if(auth()->user()->hasPermission('viewAny-client')){
            if(is_numeric($client->getAttribute('client_id'))){
                return redirect()
                    ->route('clients.edit', Client::find($validated['client_id']))
                    ->withNotify('success', $client->getAttribute('fullname'));
            }else{
                return redirect()
                    ->route('clients.index')
                    ->withNotify('success', $client->getAttribute('fullname'));
            }
        }

        return redirect()->route('close');
    }

    public function show(Client $client)
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $client,
            ]);
    }

    public function edit(Client $client)
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => route('clients.update', $client),
                'method' => "PUT",
                'data' => $client,
            ]);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $client->update($validated);

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

    public function destroy(Client $client)
    {
        if ($client->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}