<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\FolderRequest, Requests\SupportRequest};
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Support::class, 'support');
    }

    public function index()
    {
        return view('pages.supports.index')
            ->with([ 'supports' => Support::get()]);
    }

    public function create()
    {
        return view('pages.supports.edit')->with([
            'action' => route('supports.store'),
            'method' => null,
            'data' => new Support(),
        ]);
    }

    public function store(SupportRequest $request)
    {
        $support = Support::create($request->validated());

        return redirect()
            ->route('supports.edit', $support)
            ->withNotify('success', $support->getAttribute('name'));
    }

    public function show(Support $support)
    {
        return view('pages.supports.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $support,
        ]);
    }

    public function edit(Support $support)
    {
        return view('pages.supports.edit')->with([
            'action' => route('supports.update', $support),
            'method' => 'PUT',
            'data' => $support,
        ]);
    }

    public function update(SupportRequest $request, Support $support)
    {
        $validated = $request->validated();
        $support->update($validated);

        return redirect()
            ->route('supports.edit', $support)
            ->withNotify('success', $support->getAttribute('name'));
    }

    public function destroy(Support $support)
    {
        if ($support->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
