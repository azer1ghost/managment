<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\User;
use App\Services\FirebaseApi;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth')->except('viewer', 'temporaryViewerUrl');
//        $this->authorizeResource(Document::class, 'document');
//    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $limit  = $request->get('limit', 25);
        $search = $request->get('search');
        $user = $request->get('user_id');

        $documents = Document::with('user', 'documentable')
            ->when($user,   fn ($query) => $query->where('user_id', $user))
            ->when($search, fn ($query) => $query->where('name', 'LIKE', "%$search%"));

        if(!auth()->user()->isDeveloper() && !auth()->user()->isDirector() ){
            $documents = $documents->whereBelongsTo(auth()->user());
        }

        return view('pages.documents.index')->with([
            'documents' => $documents->orderByDesc('id')->paginate($limit),
            'users' => User::get(['id', 'name', 'surname'])
        ]);
    }

    public function create()
    {
        return view('pages.documents.edit')->with([
            'action' => route('documents.store'),
            'method' => null,
            'data' => null,
        ]);
    }

    public function store(DocumentRequest $request, $modelId): RedirectResponse
    {
        $modelName = $request->get('model');
        $model =  ("App\\Models\\" . $modelName)::find($modelId);
        $authID = auth()->id() ? auth()->id() : 15;
        $file = $request->file('file');
        $fileName = $authID. '-' . config('default.prefix') .  time() . '.' . $file->getClientOriginalExtension();

        $firebaseStoragePath = "Documents/$modelName/";

        $document = $model->documents()->create([
            'name' => $file->getClientOriginalName(),
            'file' => $fileName,
            'type' => $file->getClientMimeType(),
            'user_id'  => $authID,
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

    public function viewer(Request $request, Document $document)
    {
        abort_if(!$request->hasValidSignature(), 404);

        return view('pages.main.file-viewer', compact('document'));
    }

    public function temporaryViewerUrl(Document $document)
    {
        return redirect()->temporarySignedRoute(
            'documents.viewer', now()->addMinutes(30), ['document' => $document]
        );
    }

    public function edit(Document $document)
    {
        return view('pages.documents.edit')->with([
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
