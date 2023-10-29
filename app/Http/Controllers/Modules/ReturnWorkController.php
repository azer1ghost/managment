<?php

namespace App\Http\Controllers\Modules;

use App\Models\Folder;
use App\Models\Position;
use App\Http\{Controllers\Controller, Requests\ReturnWorkRequest};
use App\Models\ReturnWork;
use Illuminate\Http\Request;

class ReturnWorkController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(ReturnWork::class, 'returnWork');
//    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('pages.return-works.index')
            ->with([
                'returnWorks' => ReturnWork::when($search, fn ($query) => $query
                    ->where('name', 'like', "%".$search."%"))
                    ->paginate(25)
            ]);
    }

    public function create()
    {
        return view('pages.return-works.edit')->with([
            'action' => route('return-works.store'),
            'method' => null,
            'data' => new ReturnWork(),
        ]);
    }

    public function store(Request $request)
    {
//        $returnWork = ReturnWork::create($request->validated());
//
//        return redirect()->back()
//            ->withNotify('success', $returnWork->getAttribute('name'));
        // Form verilerini doğrulama kuralları eklemek isteyebilirsiniz.
        $validatedData = $request->validate([
            'return_reason' => 'required',
            'main_reason' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);

        // Form verilerini kullanarak yeni bir ReturnWork kaydı oluşturun
        $returnWork = new ReturnWork;
        $returnWork->work_id = $request->input('work_id');
        $returnWork->return_reason = $request->input('return_reason');
        $returnWork->main_reason = $request->input('main_reason');
        $returnWork->name = $request->input('name');
        $returnWork->phone = $request->input('phone');
        $returnWork->note = $request->input('note');

        // ReturnWork modelini veritabanına kaydedin
        $returnWork->save();

        // Başarılı bir işlem sonucu döndürün (örneğin, başarı mesajı)
//        return response()->json(['message' => 'Başarıyla kaydedildi'], 200);
    }


    public function show(ReturnWork $returnWork)
    {
        return view('pages.return-works.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $returnWork,
        ]);
    }

    public function edit(ReturnWork $returnWork)
    {
        return view('pages.return-works.edit')->with([
            'action' => route('return-works.update', $returnWork),
            'method' => 'PUT',
            'data' => $returnWork,
        ]);
    }

    public function update(ReturnWorkRequest $request, ReturnWork $returnWork)
    {
        $returnWork->sync($request->get('name'));


        return redirect()
            ->route('return-works.edit', $returnWork)
            ->withNotify('success', $returnWork->getAttribute('name'));
    }

    public function destroy(ReturnWork $returnWork)
    {
        if ($returnWork->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
 public function AjaxStore(Request $request)
    {
        $validatedData = $request->validate([
            'return_reason' => 'required',
            'main_reason' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);

        // Form verilerini kullanarak yeni bir ReturnWork kaydı oluşturun
        $returnWork = new ReturnWork;
        $returnWork->work_id = $request->input('work_id');
        $returnWork->return_reason = $request->input('return_reason');
        $returnWork->main_reason = $request->input('main_reason');
        $returnWork->name = $request->input('name');
        $returnWork->phone = $request->input('phone');
        $returnWork->note = $request->input('note');

        // ReturnWork modelini veritabanına kaydedin
        $returnWork->save();

        // Başarılı bir işlem sonucu döndürün (örneğin, başarı mesajı)
//        return response()->json(['message' => 'Başarıyla kaydedildi'], 200);
    }

}
