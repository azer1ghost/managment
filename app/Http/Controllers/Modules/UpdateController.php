<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $view = $type == 'table' ? 'index' : 'timeline';
        $search = $request->get('search');
        $updates = Update::with('updates')
            ->when($search, fn($query) => $query->where('name', 'LIKE', "%$search%"))
            ->latest('datetime');

        if($request->has('type') && $type == 'table'){
            $updates = $updates->with('user', 'parent')->simplePaginate();
        }else{
            $updates = $updates->whereNull('parent_id')->get()->groupBy('datetime');
        }

        return view('pages.updates.' . $view)->with([
            'updates' => $updates
        ]);
    }

    public function create()
    {
        return view('pages.updates.edit')->with([
            'action' => route('updates.store'),
            'method' => 'POST',
            'data' => null,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::whereNull('parent_id')->latest('datetime')->get(['id', 'name', 'datetime'])
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
        return view('pages.updates.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $update,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::whereNull('parent_id')->where('id', '!=', $update->getAttribute('id'))->latest('datetime')->get(['id', 'name', 'datetime'])
        ]);
    }

    public function edit(Update $update)
    {
        return view('pages.updates.edit')->with([
            'action' => route('updates.update', $update),
            'method' => 'PUT',
            'data' => $update,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Update::statuses(),
            'updates' => Update::whereNull('parent_id')->where('id', '!=', $update->getAttribute('id'))->latest('datetime')->get(['id', 'name', 'datetime'])
        ]);
    }

    public function update(UpdateRequest $request, Update $update)
    {
        $validated = $request->validated();

        if($update->getAttribute('parent_id') != $validated['parent_id']){
            if($update->updates()->count() > 0){
                return back()
                    ->withNotify('error', $update->getAttribute('name') . " has children", true);
            }
        }

        $update->update($validated);

        return redirect()
            ->route('updates.edit', $update)
            ->withNotify('success', $update->getAttribute('name'));
    }


    public function destroy(Update $update)
    {
        if($update->updates()->count() > 0){
            return response()->json($update->getAttribute('name') . " has children", 405);
        }

        if ($update->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
