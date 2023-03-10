<?php

namespace App\Http\Controllers\Modules;

use App\Models\Company;
use App\Models\IsoDocument;
use App\Http\{Controllers\Controller, Requests\IsoDocumentRequest};
use Illuminate\Http\Request;

class IsoDocumentController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(IsoDocument::class, 'isoDocument');
//    }

    public function index()
    {
        return view('pages.iso-documents.index')
            ->with([
                'companies' => Company::get(['id','name']),
                'isoDocuments' => IsoDocument::get()
            ]);
    }


    public function create()
    {
        return view('pages.iso-documents.edit')->with([
            'action' => route('iso-documents.store'),
            'method' => 'POST',
            'data' => new IsoDocument(),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(IsoDocumentRequest $request)
    {
        $isoDocument = IsoDocument::create($request->validated());

        return redirect()
            ->route('iso-documents.edit', $isoDocument)
            ->withNotify('success', $isoDocument->getAttribute('name'));
    }

    public function show(IsoDocument $isoDocument)
    {
        return view('pages.iso-documents.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $isoDocument,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(IsoDocument $isoDocument)
    {
        return view('pages.iso-documents.edit')->with([
            'action' => route('iso-documents.update', $isoDocument),
            'method' => 'PUT',
            'data' => $isoDocument,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(IsoDocumentRequest $request, IsoDocument $isoDocument)
    {
        $validated = $request->validated();
        $isoDocument->update($validated);

        return redirect()
            ->route('iso-documents.edit', $isoDocument)
            ->withNotify('success', $isoDocument->getAttribute('name'));
    }

    public function destroy(IsoDocument $isoDocument)
    {
        if ($isoDocument->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
