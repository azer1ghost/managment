<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Document::class, 'document');
    }

    public function index()
    {
        return view('panel.pages.documents.index')->with([
            'documents' => Document::paginate(10)
        ]);
    }

    public function create()
    {

        return view('panel.pages.documents.edit')->with([
            'action' => route('documents.store'),
            'method' => null,
            'data' => null,
        ]);
    }

    public function store(DocumentRequest $request)
    {
        $document = Document::create($request->validated());

        return redirect()
            ->route('documents.edit',$document)
            ->withNotify('success', $document->getAttribute('name'));
    }

    public function show(Document $document)
    {
        return view('panel.pages.documents.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $document,
        ]);
    }

    public function edit(Document $document)
    {
        return view('panel.pages.documents.edit')->with([
            'action' => route('documents.update', $document),
            'method' => 'PUT',
            'data' => $document,
        ]);
    }

    public function update(DocumentRequest $request, Document $document)
    {
       $document->update($request->validated());

        return redirect()
            ->route('documents.edit',$document)
            ->withNotify('success', $document->getAttribute('name'));
    }

    public function destroy(Document $document)
    {
        if ($document->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
