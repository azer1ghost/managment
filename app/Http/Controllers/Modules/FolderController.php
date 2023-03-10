<?php

namespace App\Http\Controllers\Modules;

use App\Models\Company;
use App\Http\{Controllers\Controller, Requests\FolderRequest};
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Folder::class, 'folder');
    }

    public function index()
    {
        return view('pages.folders.index')
            ->with([
                'companies' => Company::get(['id','name']),
                'folders' => Folder::get()
            ]);
    }

    public function create()
    {
        return view('pages.folders.edit')->with([
            'action' => route('folders.store'),
            'method' => null,
            'data' => new Folder(),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(FolderRequest $request)
    {
        $folder = Folder::create($request->validated());

        return redirect()
            ->route('folders.edit', $folder)
            ->withNotify('success', $folder->getAttribute('name'));
    }

    public function show(Folder $folder)
    {
        return view('pages.folders.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $folder,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(Folder $folder)
    {
        return view('pages.folders.edit')->with([
            'action' => route('folders.update', $folder),
            'method' => 'PUT',
            'data' => $folder,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(FolderRequest $request, Folder $folder)
    {
        $validated = $request->validated();
        $folder->update($validated);

        return redirect()
            ->route('folders.edit', $folder)
            ->withNotify('success', $folder->getAttribute('name'));
    }

    public function destroy(Folder $folder)
    {
        if ($folder->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
