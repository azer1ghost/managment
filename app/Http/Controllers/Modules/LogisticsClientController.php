<?php

namespace App\Http\Controllers\Modules;

use App\Models\SalesClient;
use App\Models\User;
use App\Http\{Controllers\Controller, Requests\LogisticsClientRequest};
use App\Models\LogisticsClient;
use Illuminate\Http\Request;

class LogisticsClientController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(LogisticsClient::class, 'logisticsClient');
//    }
    public function search(Request $request): object
    {
        $clients = LogisticsClient::where('name', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('phone', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('email', 'LIKE', "%{$request->get('search')}%")
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        $clientsArray = [];

        foreach ($clients as $client) {
            $clientsArray[] = [
                "id"   => $client->id,
                "text" => "{$client->name}",
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
        $limit = $request->get('limit', 25);
        $search = $request->get('search');
        $user_id = $request->get('user');

        return view('pages.logistics-clients.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'logisticsClients' => LogisticsClient::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->when($user_id, fn ($query) => $query->where('user_id', $user_id))
                    ->latest()
                    ->paginate($limit),
            ]);

    }

    public function create()
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => route('logistic-clients.store'),
            'method' => null,
            'data' => new LogisticsClient(),
        ]);
    }

    public function store(LogisticsClientRequest $request)
    {
        $validated = $request->validated();
        $logisticsClient = LogisticsClient::create($validated);

        return redirect()
            ->route('logistic-clients.edit', $logisticsClient)
            ->withNotify('success', $logisticsClient->getAttribute('name'));
    }

    public function show(LogisticsClient $logisticsClient)
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $logisticsClient,
        ]);
    }

    public function edit(LogisticsClient $logisticsClient)
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => route('logistic-clients.update', $logisticsClient),
            'method' => 'PUT',
            'data' => $logisticsClient,
        ]);
    }

    public function update(LogisticsClientRequest $request, LogisticsClient $logisticsClient)
    {
        $validated = $request->validated();
        $logisticsClient->update($validated);

        return redirect()
            ->route('logistic-clients.edit', $logisticsClient)
            ->withNotify('success', $logisticsClient->getAttribute('name'));
    }

    public function destroy(LogisticsClient $logisticsClient)
    {
        if ($logisticsClient->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
