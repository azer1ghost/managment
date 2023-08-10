<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandRequest;
use App\Http\Requests\SummitRequest;
use App\Models\Change;
use App\Models\Command;
use App\Models\Company;
use App\Models\Summit;
use Illuminate\Http\Request;
use App\Models\User;

class SummitController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(Summit::class, 'summit');
//    }

    public function index(Request $request)
    {
        $statuses = Summit::statuses();
        $clubNames = Summit::clubNames();
        $search = $request->get('search');

        return view('pages.summits.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'statuses' => $statuses,
                'clubNames' => $clubNames,
                'summits' => Summit::when($search, fn ($query) => $query
                    ->where('club', 'like', "%".$search."%"))
                    ->orderBy('date')
                    ->paginate(25)
            ]);
    }

    public function create(Request $request)
    {

        return view('pages.summits.edit')->with([
            'action' => route('summits.store'),
            'method' => 'POST',
            'data' => new Summit(),
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Summit::statuses(),
            'clubNames' => Summit::clubNames()
        ]);
    }

    public function store(SummitRequest $request)
    {
        $summit = Summit::create($request->validated());
        $summit->users()->sync($request->get('users'));

        return redirect()
            ->route('summits.edit', $summit)
            ->withNotify('success', $summit->getAttribute('id'));
    }

    public function show(Summit $summit)
    {
        return view('pages.summits.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $summit,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Summit::statuses(),
            'clubNames' => Summit::clubNames(),
        ]);
    }

    public function edit(Summit $summit)
    {
        return view('pages.summits.edit')->with([
            'action' => route('summits.update', $summit),
            'method' => 'PUT',
            'data' => $summit,
            'users' => User::get(['id', 'name', 'surname']),
            'statuses' => Summit::statuses(),
            'clubNames' => Summit::clubNames(),
        ]);
    }

    public function update(SummitRequest $request, Summit $summit)
    {
        $summit->update($request->validated());
        $summit->users()->sync($request->get('users'));

        return redirect()
            ->route('summits.edit', $summit)
            ->withNotify('success', $summit->getAttribute('name'));
    }

    public function destroy(Summit $summit)
    {
        if ($summit->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
    public function sortable(Request $request)
    {
        foreach ($request->get('item') as $key => $value) {
            $summit = Command::find($value);
            $summit->ordering = $key;
            $summit->save();
        }
    }
}
