<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\salesClientRequest;
use App\Models\SalesClient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalesClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SalesClient::class, 'sales_client');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('panel.pages.sales-clients.index')
            ->with([
                'sales_clients' => SalesClient::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->paginate($limit),
            ]);
    }

    public function create()
    {
        return view('panel.pages.sales-clients.edit')
            ->with([
                'action' => route('sales-clients.store'),
                'method' => 'POST',
                'data'   => new SalesClient(),
                'users'  => User::get(['id', 'name', 'surname']),
            ]);
    }

    public function store(SalesClientRequest $request): RedirectResponse
    {
        $request->user()->salesInquiryUsers()->create($request->validated());

        return redirect()
            ->route('sales-clients.index')
            ->withNotify('success', $request->get('name'));
    }

    public function show(SalesClient $salesClient)
    {
        return view('panel.pages.sales-clients.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $salesClient,
            ]);
    }

    public function edit(SalesClient $salesClient)
    {
        return view('panel.pages.sales-clients.edit')
            ->with([
                'action' => route('sales-clients.update', $salesClient),
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
