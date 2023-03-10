<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\InternalDocumentRequest};
use App\Models\{Department, InternalDocument, User};
use Illuminate\Http\Request;

class InternalDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(InternalDocument::class, 'internal_document');
    }

    public function index(Request $request)
    {
        $company = $request->get('company_id', 3);

        return view('pages.internal-documents.index')
            ->with(['internalDocuments' => InternalDocument::get()]);
//        when($company, fn($query) => $query
//            ->where('company_id', $company))
    }

    public function create()
    {
        return view('pages.internal-documents.edit')->with([
            'action' => route('internal-documents.store'),
            'method' => null,
            'data' => new InternalDocument(),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function store(InternalDocumentRequest $request)
    {
        $internalDocument = InternalDocument::create($request->validated());

        return redirect()
            ->route('internal-documents.edit', $internalDocument)
            ->withNotify('success', $internalDocument->getAttribute('document_name'));
    }

    public function show(InternalDocument $internalDocument)
    {
        return view('pages.internal-documents.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $internalDocument,
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function edit(InternalDocument $internalDocument)
    {
        return view('pages.internal-documents.edit')->with([
            'action' => route('internal-documents.update', $internalDocument),
            'method' => 'PUT',
            'data' => $internalDocument,
            'users' => User::get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function update(InternalDocumentRequest $request, InternalDocument $internalDocument)
    {
        $validated = $request->validated();
        $internalDocument->update($validated);

        return redirect()
            ->route('internal-documents.edit', $internalDocument)
            ->withNotify('success', $internalDocument->getAttribute('name'));
    }

    public function destroy(InternalDocument $internalDocument)
    {
        if ($internalDocument->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
