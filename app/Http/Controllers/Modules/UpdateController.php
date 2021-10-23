<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index()
    {
        return view('panel.pages.updates.index')->with([
            'updates' => Update::paginate(10),
        ]);
    }

    public function create()
    {
        return view('panel.pages.updates.edit')->with([
            'action' => route('updates.store'),
            'method' => 'POST',
            'data' => null,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::get(['id', 'name'])->pluck('name', 'id')->toArray()
        ]);
    }

    public function store(UpdateRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $update = Update::create($validated);

        return redirect()
            ->route('updates.edit', $update)
            ->withNotify('success', $update->getAttribute('name'));
    }

    public function show(Update $update)
    {
        return view('panel.pages.updates.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $update,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::where('id', '!=', $update->id)->get(['id', 'name'])->pluck('name', 'id')->toArray()
        ]);
    }

    public function edit(Update $update)
    {
        return view('panel.pages.updates.edit')->with([
            'action' => route('updates.update', $update),
            'method' => 'PUT',
            'data' => $update,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::where('id', '!=', $update->id)->get(['id', 'name'])->pluck('name', 'id')->toArray()
        ]);
    }

    public function update(UpdateRequest $request, Update $update)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $update->update($validated);

        return redirect()
            ->route('updates.edit', $update)
            ->withNotify('success', $update->getAttribute('name'));
    }


    public function destroy(Update $update)
    {
        if ($update->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
