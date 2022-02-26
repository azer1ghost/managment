<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\SalesClientRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesClient;
use App\Models\Client;
use App\Models\User;

class SalesClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SalesClient::class, 'sales_client');
    }

    public function search(Request $request)
    {
        $salesclients = SalesClient::where('name', 'LIKE', "%{$request->get('search')}%")
            ->orWhere('voen', 'LIKE', "%{$request->get('search')}%")
            ->limit(10)
            ->latest()
            ->get(['id', 'name', 'voen']);

//
//        $clients = Client::where('fullname', 'LIKE', "%{$request->get('search')}%")
//            ->orWhere('voen', 'LIKE', "%{$request->get('search')}%")
//            ->limit(10)
//            ->latest()
//            ->get(['id', 'fullname', 'voen']);

        $clientsArray = [];

        foreach ($salesclients as $client) {
            $clientsArray[] = [
                "id" => $client->id,
                "text" => "{$client->name_with_voen}",
            ];
        }
//
//        foreach ($clients as $client) {
//            $clientsArray[] = [
//                "id"   => $client->id,
//                "text" => "{$client->fullname_with_voen}",
//            ];
//        }

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

        return view('pages.sales-clients.index')
            ->with([
                'users' => User::has('salesInquiryUsers')->get(['id', 'name', 'surname']),
                'sales_clients' => SalesClient::query()
                    ->when(!auth()->user()->hasPermission('viewAll-salesInquiry'), fn ($query) => $query->where('user_id', $request->user()->id))
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->when($user_id, fn ($query) => $query->where('user_id', $user_id))
                    ->latest()
                    ->paginate($limit),
            ]);
    }

    public function create()
    {
        return view('pages.sales-clients.edit')
            ->with([
                'action' => route('sales-client.store'),
                'method' => 'POST',
                'data'   => new SalesClient(),
                'users'  => User::get(['id', 'name', 'surname']),
            ]);
    }

    public function store(SalesClientRequest $request): RedirectResponse
    {
        $request->user()->salesInquiryUsers()->create($request->validated());

        if($request->get('close') == 1) {
            return redirect()->route('close');
        }

        return redirect()
            ->route('sales-client.index')
            ->withNotify('success', $request->get('name'));
    }

    public function show(SalesClient $salesClient)
    {
        return view('pages.sales-clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $salesClient,
            ]);
    }

    public function edit(SalesClient $salesClient)
    {
        return view('pages.sales-clients.edit')
            ->with([
                'action' => route('sales-client.update', $salesClient),
                'method' => "PUT",
                'data' => $salesClient,
            ]);
    }

    public function update(SalesClientRequest $request, SalesClient $salesClient): RedirectResponse
    {
        $salesClient->update($request->validated());

        return back()->withNotify('info', $salesClient->getAttribute('name'));
    }

    public function destroy(SalesClient $salesClient)
    {
        if ($salesClient->delete()) {

            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
