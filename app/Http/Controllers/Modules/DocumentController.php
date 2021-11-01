<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Services\FirebaseApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

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

    public function store(DocumentRequest $request, $modelId): RedirectResponse
    {
        $modelName = $request->get('model');
        $model =  ("App\\Models\\" . $modelName)::find($modelId);

        $file = $request->file('file');
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        $firebaseStoragePath = "Documents/$modelName/";

        $document = $model->documents()->create([
            'name' => $file->getClientOriginalName(),
            'file' => $fileName,
            'type' => $file->getClientMimeType(),
            'user_id'  => auth()->id(),
            'size'  => $file->getSize()
        ]);

        if($document){
            $localFolder = storage_path('app/firebase-temp-uploads/');
            if ($file->move($localFolder, $fileName)) {
                $uploadedFile = fopen($localFolder . $fileName, 'r');
                (new FirebaseApi)->getDoc()->upload($uploadedFile, ['name' => $firebaseStoragePath . $fileName]);
                // will remove from local laravel folder
                unlink($localFolder . $fileName);
            }
        }

        return back()->withNotify('success', $document->getAttribute('name'));
    }

    public function show(Document $document)
    {
        return public_path('storage/avatars/4qHRyeq4XXjv6fIqhnYS6SQFIGDZGX5ZUNtgn2qf.jpg');
    }

    public function edit(Document $document)
    {
        return view('panel.pages.documents.edit')->with([
            'action' => route('documents.update', $document),
            'method' => 'PUT',
            'data' => $document,
        ]);
    }

    public function update(DocumentRequest $request, Document $document): RedirectResponse
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
