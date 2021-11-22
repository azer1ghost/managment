<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsanImzaRequest;
use App\Models\AsanImza;
use App\Models\Company;
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

    public function search(Request $request): object
    {
        $asanImzaUsers = AsanImza::whereHas('user', function ($query) use ($request){
            $query->where('name', 'LIKE', "%{$request->get('search')}%")
                  ->orWhere('surname', 'LIKE', "%{$request->get('search')}%");
        })
            ->limit(10)
            ->get();

        $asanImzaUsersArray = [];

        foreach ($asanImzaUsers as $asanImzaUser) {
            $asanImzaUsersArray[] = [
                "id"   => $asanImzaUser->id,
                "text" => "{$asanImzaUser->getRelationValue('user')->getAttribute('fullname')} ({$asanImzaUser->getRelationValue('company')->getAttribute('name')})",
            ];
        }

        return (object) [
            'results' => $asanImzaUsersArray,
            'pagination' => [
                "more" => false
            ]
        ];
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $company = $request->get('company');

        return view('panel.pages.asan-imza.index')
            ->with([
                'asan_imzas' => AsanImza::query()
                    ->when($company, fn($query) => $query->where('company_id', $company))->simplePaginate($limit),
                'companies' => Company::get(['id', 'name']),
            ]);
    }

    public function create()
    {
        return view('panel.pages.asan-imza.edit')
            ->with([
                'action' => route('asan-imza.store'),
                'method' => 'POST',
                'data' => new AsanImza(),
                'companies' => Company::get(['id', 'name']),
                'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function store(AsanImzaRequest $request): RedirectResponse
    {
        $asanImza = AsanImza::create($request->validated());

        return redirect()
            ->route('asan-imza.index')
            ->withNotify('success', $asanImza->getAttribute('name'));
    }

    public function show(AsanImza $asanImza)
    {
        return view('panel.pages.asan-imza.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $asanImza,
                'companies' => Company::get(['id', 'name']),
                'users' => User::oldest('name')->get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function edit(AsanImza $asanImza)
    {
        return view('panel.pages.asan-imza.edit')
            ->with([
                'action' => route('asan-imza.update', $asanImza),
                'method' => "PUT",
                'data' => $asanImza,
                'companies' => Company::get(['id', 'name']),
                'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function update(AsanImzaRequest $request, AsanImza $asanImza): RedirectResponse
    {
        $asanImza->update($request->validated());

        return back()->withNotify('info', $asanImza->getAttribute('name'));
    }

    public function destroy(AsanImza $asanImza)
    {
        if ($asanImza->delete()) {

            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
