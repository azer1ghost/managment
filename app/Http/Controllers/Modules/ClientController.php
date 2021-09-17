<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Client::class, 'client');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('panel.pages.clients.index')
            ->with([
                'clients' => Client::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%".$search."%")
                        ->orWhere('surname', 'like', "%".$search."%")
                        ->orWhere('id', $search))
                    ->simplePaginate(10)
            ]);
    }

    public function create()
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => route('clients.store'),
                'method' => null,
                'data' => null,
            ]);
    }

    public function store(ClientRequest  $request)
    {
        $validated = $request->validated();

        $client = Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->withNotify('success', $client->getAttribute('fullname'));
    }

    public function show(Client $client)
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $client
            ]);
    }

    public function edit(Client $client)
    {
        return view('panel.pages.clients.edit')
            ->with([
                'action' => route('clients.update', $client),
                'method' => "PUT",
                'data' => $client
            ]);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $client->update($validated);

        return back()->withNotify('info', $client->getAttribute('name'));

    }

    public function destroy(Client $client)
    {
        if ($client->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}