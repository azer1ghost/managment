<?php

namespace App\Http\Controllers\Modules;

use App\Models\Company;
use App\Models\Necessary;
use App\Http\{Controllers\Controller, Requests\NecessaryRequest};
use Illuminate\Http\Request;

class NecessaryController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(Necessary::class, 'necessary');
//    }

    public function index()
    {
        return view('pages.necessaries.index')
            ->with([
                'necessaries' => Necessary::get()
            ]);
    }


    public function create()
    {
        return view('pages.necessaries.edit')->with([
            'action' => route('necessaries.store'),
            'method' => 'POST',
            'data' => new Necessary(),
        ]);
    }

    public function store(NecessaryRequest $request)
    {
        $necessary = Necessary::create($request->validated());

        return redirect()
            ->route('necessaries.edit', $necessary)
            ->withNotify('success', $necessary->getAttribute('name'));
    }

    public function show(Necessary $necessary)
    {
        return view('pages.necessaries.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $necessary,
        ]);
    }

    public function edit(Necessary $necessary)
    {
        return view('pages.necessaries.edit')->with([
            'action' => route('necessaries.update', $necessary),
            'method' => 'PUT',
            'data' => $necessary,
        ]);
    }

    public function update(NecessaryRequest $request, Necessary $necessary)
    {
        $validated = $request->validated();
        $necessary->update($validated);

        return redirect()
            ->route('necessaries.edit', $necessary)
            ->withNotify('success', $necessary->getAttribute('name'));
    }

    public function destroy(Necessary $necessary)
    {
        if ($necessary->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
