<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Services\FirebaseApi;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Document::class, 'document');
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $documents = Document::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"));

        if(!auth()->user()->isDeveloper()){
            $documents = $documents->whereBelongsTo(auth()->user());
        }

        return view('panel.pages.documents.index')->with([
            'documents' => $documents->paginate(10)
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
        $fileName = auth()->id() . '-' . config('default.prefix') .  time() . '.' . $file->getClientOriginalExtension();

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
                // will remove from storage folder
                unlink($localFolder . $fileName);
            }
        }

        return back()->withNotify('success', $document->getAttribute('name'));
    }

    public function show(Document $document)
    {
        $url = (new FirebaseApi)->getDoc()->object("Documents/{$document->module()}/{$document->getAttribute('file')}")->signedUrl(
            new \DateTime('1 min')
        );

        return response(file_get_contents($url))
            ->withHeaders([
                'Content-Type' => $document->getAttribute('type')
            ]);
    }

    public function viewer(Document $document)
    {
        return view('panel.pages.main.file-viewer', compact('document'));
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
            // (new FirebaseApi)->getDoc()->object("Documents/Task/{$document->getAttribute('file')}")->delete();
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
