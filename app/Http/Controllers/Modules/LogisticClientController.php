<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\LogisticClientRequest};
use App\Models\LogisticClient;
use Illuminate\Http\Request;

class LogisticClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(LogisticClient::class, 'logisticClient');
    }

    public function index()
    {
        return view('pages.logistics-clients.index')
            ->with([ 'logisticClients' => LogisticClient::get()]);
    }

    public function create()
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => route('logistic-clients.store'),
            'method' => null,
            'data' => new LogisticClient(),
        ]);
    }

    public function store(LogisticClientRequest $request)
    {
        $validated = $request->validated();
        $accessRate = LogisticClient::create($validated);

        return redirect()
            ->route('logistics-clients.edit', $accessRate)
            ->withNotify('success', $accessRate->getAttribute('name'));
    }

    public function show(LogisticClient $accessRate)
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $accessRate,
        ]);
    }

    public function edit(LogisticClient $accessRate)
    {
        return view('pages.logistics-clients.edit')->with([
            'action' => route('logistics-clients.update', $accessRate),
            'method' => 'PUT',
            'data' => $accessRate,
        ]);
    }

    public function update(LogisticClientRequest $request, LogisticClient $accessRate)
    {
        $validated = $request->validated();
        $accessRate->update($validated);

        return redirect()
            ->route('logistics-clients.edit', $accessRate)
            ->withNotify('success', $accessRate->getAttribute('name'));
    }

    public function destroy(LogisticClient $accessRate)
    {
        if ($accessRate->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
