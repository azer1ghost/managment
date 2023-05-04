<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProtocolRequest;
use App\Models\Change;
use App\Models\Company;
use App\Models\Protocol;
use Illuminate\Http\Request;
use App\Models\User;

class ProtocolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Protocol::class, 'protocol');
    }

    public function index(Request $request)
    {
        $company = $request->get('company_id', 3);
        $search = $request->get('search');

        return view('pages.protocols.index')
            ->with([
                'companies' => Company::get(['id','name']),
                'users' => User::get(['id', 'name', 'surname']),
                'protocols' => Protocol::when($search, fn($query) => $query
                    ->where('content', 'like', "%" . $search . "%"))
                    ->when($company, fn($query) => $query
                    ->where('company_id', $company))
                    ->latest()
                    ->paginate(25)]);
    }

    public function create()
    {
        return view('pages.protocols.edit')->with([
            'action' => route('protocols.store'),
            'method' => 'POST',
            'data' => new Protocol(),
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(ProtocolRequest $request)
    {
        $protocol = Protocol::create($request->validated());

        return redirect()
            ->route('protocols.edit', $protocol)
            ->withNotify('success', $protocol->getAttribute('sender'));
    }

    public function show(Protocol $protocol)
    {
        return view('pages.protocols.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $protocol,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(Protocol $protocol)
    {
        return view('pages.protocols.edit')->with([
            'action' => route('protocols.update', $protocol),
            'method' => 'PUT',
            'data' => $protocol,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(ProtocolRequest $request, Protocol $protocol)
    {
        $protocol->update($request->validated());

        return redirect()
            ->route('protocols.edit', $protocol)
            ->withNotify('success', $protocol->getAttribute('name'));
    }

    public function destroy(Protocol $protocol)
    {
        if ($protocol->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
