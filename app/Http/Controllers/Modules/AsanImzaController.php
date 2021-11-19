<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsanImzaRequest;
use App\Http\Requests\CompanyRequest;
use App\Models\AsanImza;
use App\Models\Company;
use App\Models\Social;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AsanImzaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(AsanImza::class, 'asan_imza');
    }

    public function index(Request $request)
    {
        $limit  = $request->get('limit', 25);
        $company  = $request->get('company');


        return view('panel.pages.asan-imzas.index')
                ->with([
                    'asan_imzas' => AsanImza::query()
                           ->when($company, fn ($query) => $query->where('company_id', $company))->simplePaginate($limit),
                    'companies' => Company::get(['id', 'name']),

                ]);
    }


    public function create()
    {
        return view('panel.pages.asan-imzas.edit')
            ->with([
                'action' => route('asan-imzas.store'),
                'method' => null,
                'data' => null,
                'companies'=>Company::get(['id', 'name']),
                'users'=>User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }


    public function store(AsanImzaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $asan_imza = AsanImza::create($validated);


        return redirect()
            ->route('asan-imzas.index')
            ->withNotify('success', $asan_imza->getAttribute('name'));
    }

    public function show(AsanImza $asan_imza)
    {
        return view('panel.pages.asan-imzas.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $asan_imza,
                'companies'=>Company::get(['id', 'name']),
                'users'=>User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function edit(AsanImza $asan_imza)
    {
        return view('panel.pages.asan-imzas.edit')
            ->with([
                'action' => route('asan-imzas.update', $asan_imza),
                'method' => "PUT",
                'data' => $asan_imza,
                'companies'=>Company::get(['id', 'name']),
                'users'=>User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function update(AsanImzaRequest $request, AsanImza $asan_imza): RedirectResponse
    {
        $validated = $request->validated();


        $asan_imza->update($validated);


        return back()->withNotify('info', $asan_imza->getAttribute('name'));
    }

    public function destroy(AsanImza $asanImza)
    {
        if ($asanImza->delete()) {
//            if (Storage::exists($company->getAttribute('logo'))) {
//                Storage::delete($company->getAttribute('logo'));
//            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
