<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\InternalRelationRequest};
use App\Models\{Department, InternalRelation, User};

class InternalRelationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(InternalRelation::class, 'internal_relation');
    }

    public function index()
    {
        return view('pages.internal_relations.index')
            ->with([ 'internalRelations' => InternalRelation::latest()->get() ]);
    }

    public function create()
    {
        return view('pages.internal_relations.edit')->with([
            'action' => route('internal-relations.store'),
            'method' => null,
            'data' => new InternalRelation(),
            'users' => User::get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function store(InternalRelationRequest $request)
    {
        $internalRelation = InternalRelation::create($request->validated());

        return redirect()
            ->route('internal-relations.edit', $internalRelation)
            ->withNotify('success', $internalRelation->getAttribute('name'));
    }

    public function show(InternalRelation $internalRelation)
    {
        return view('pages.internal_relations.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $internalRelation,
            'users' => User::get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function edit(InternalRelation $internalRelation)
    {
        return view('pages.internal_relations.edit')->with([
            'action' => route('internal-relations.update', $internalRelation),
            'method' => 'PUT',
            'data' => $internalRelation,
            'users' => User::get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function update(InternalRelationRequest $request, InternalRelation $internalRelation)
    {
        $internalRelation->update($request->validated());

        return redirect()
            ->route('internal-relations.edit', $internalRelation)
            ->withNotify('success', $internalRelation->getAttribute('name'));
    }

    public function destroy(InternalRelation $internalRelation)
    {
        if ($internalRelation->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
