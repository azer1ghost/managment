<?php

namespace App\Http\Controllers\Modules;

use App\Models\Company;
use App\Models\Folder;
use App\Models\Position;
use App\Http\{Controllers\Controller, Requests\AccessRateRequest};
use App\Models\AccessRate;
use Illuminate\Http\Request;

class AccessRateController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(AccessRate::class, 'accessRate');
//    }

    public function index(Request $request)
    {
        $company = $request->get('company_id', 3);

        return view('pages.access-rates.index')
            ->with([
                'positions' => Position::get(['id', 'name']),
                'folders' => Folder::get()
                ->when($company, fn($query) => $query
                    ->where('company_id', $company))
            ]);
    }

    public function create()
    {
        return view('pages.access-rates.edit')->with([
            'action' => route('access-rates.store'),
            'method' => null,
            'data' => new AccessRate(),
            'positions' => Position::get(['id', 'name']),
            'folders' => Folder::get(['id', 'name']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(AccessRateRequest $request)
    {
        $validated = $request->validated();
        $validated['is_readonly'] = $request->has('is_readonly');
        $validated['is_change'] = $request->has('is_change');
        $validated['is_print'] = $request->has('is_print');
        $accessRate = AccessRate::create($validated);
        $accessRate->positions()->sync($request->get('positions'));

        return redirect()
            ->route('access-rates.edit', $accessRate)
            ->withNotify('success', $accessRate->getAttribute('folder_id'));
    }

    public function show(AccessRate $accessRate)
    {
        return view('pages.access-rates.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $accessRate,
            'positions' => Position::get(['id', 'name']),
            'folders' => Folder::get(['id', 'name'])
        ]);
    }

    public function edit(AccessRate $accessRate)
    {
        return view('pages.access-rates.edit')->with([
            'action' => route('access-rates.update', $accessRate),
            'method' => 'PUT',
            'data' => $accessRate,
            'positions' => Position::get(['id', 'name']),
            'folders' => Folder::get(['id', 'name'])
        ]);
    }

    public function update(AccessRateRequest $request, AccessRate $accessRate)
    {
        $validated = $request->validated();
        $validated['is_readonly'] = $request->has('is_readonly');
        $validated['is_change'] = $request->has('is_change');
        $validated['is_print'] = $request->has('is_print');
        $accessRate->update($validated);
        $accessRate->positions()->sync($request->get('positions'));


        return redirect()
            ->route('access-rates.edit', $accessRate)
            ->withNotify('success', $accessRate->getAttribute('name'));
    }

    public function destroy(AccessRate $accessRate)
    {
        if ($accessRate->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
