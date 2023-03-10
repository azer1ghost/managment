<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\SentDocumentRequest};
use App\Models\{Company, Department, SentDocument, User};
use Illuminate\Http\Request;

class SentDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SentDocument::class, 'sent_document');
    }

    public function index(Request $request)
    {
        $company = $request->get('company_id', 3);

        return view('pages.sent-documents.index')
            ->with([
                'companies' => Company::get(['id','name']),
                'sentDocuments' => SentDocument::when($company, fn($query) => $query
                    ->where('company_id', $company))->orderByDesc('sent_date')->get()
            ]);
    }


    public function create()
    {
        return view('pages.sent-documents.edit')->with([
            'action' => route('sent-documents.store'),
            'method' => null,
            'data' => new SentDocument(),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(SentDocumentRequest $request)
    {
        $sentDocument = SentDocument::create($request->validated());

        return redirect()
            ->route('sent-documents.edit', $sentDocument)
            ->withNotify('success', $sentDocument->getAttribute('document_name'));
    }

    public function show(SentDocument $sentDocument)
    {
        return view('pages.sent-documents.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $sentDocument,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(SentDocument $sentDocument)
    {
        return view('pages.sent-documents.edit')->with([
            'action' => route('sent-documents.update', $sentDocument),
            'method' => 'PUT',
            'data' => $sentDocument,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(SentDocumentRequest $request, SentDocument $sentDocument)
    {
        $validated = $request->validated();
        $sentDocument->update($validated);

        return redirect()
            ->route('sent-documents.edit', $sentDocument)
            ->withNotify('success', $sentDocument->getAttribute('overhead_num'));
    }

    public function destroy(SentDocument $sentDocument)
    {
        if ($sentDocument->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
