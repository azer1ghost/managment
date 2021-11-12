<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);
        $company  = $request->get('company');
        $type  = $request->get('type') ?? 1;

        return view('panel.pages.users.index')
            ->with([
                'users' => User::query()
                    ->when($search,
                        fn ($query) => $query->where('name', 'like', "%".$search."%")
                                             ->orWhere('surname', 'like', "%".$search."%")
                                             ->orWhere('fin', 'like', "%".$search."%")
                                             ->orWhere('id', $search))
                    ->when($company, fn ($query) => $query->where('company_id', $company))
                    ->when($type, function ($query, $type){
                        switch ($type){
                            case 1:
                                $query->where('is_partner', 0);
                                break;
                            case 2:
                                $query->where('is_partner', 1);
                                break;
                        }
                    })
                    ->simplePaginate($limit),
                'companies' => Company::get(['id', 'name']),
                'types' => User::types()
            ]);
    }

    public function create()
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => route('users.store'),
                'method' => null,
                'data' => null,
                'roles'  => Role::all()->pluck('name','id')->toArray(),
                'departments' => Department::all()->pluck('name', 'id')->toArray(),
                'companies' => Company::all()->pluck('name', 'id')->toArray(),
                'positions' => Position::all()->pluck('name', 'id')->toArray(),
                'directorPositions' => Position::whereHas('role', fn ($q) => $q->where('key', 'director'))->pluck('name', 'id')->toArray(),
                'serial_pattern' => User::serialPattern(),

            ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $validated['verify_code'] = rand(111111, 999999);

        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];

        if ($request->file('avatar')) {

            $avatar = $request->file('avatar');

            $validated['avatar'] = $avatar->storeAs('avatars', $avatar->hashName());
        }

        $user = User::create($validated);

        // update user defaults
        $reversColumns = array_column($request->get('defaults') ?? [], 'value', 'parameter_id');
        $user->defaults()->sync(syncResolver($reversColumns,'value'));

        return redirect()
            ->route('users.index')
            ->withNotify('success', $user->getAttribute('fullname'));
    }


    public function show(User $user)
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => null,
                'method' => null,
                'roles'  => Role::all()->pluck('name','id')->toArray(),
                'departments' => Department::all()->pluck('name', 'id')->toArray(),
                'companies' => Company::all()->pluck('name', 'id')->toArray(),
                'positions' => $user->getRelationValue('department')->positions()->pluck('name', 'id')->toArray(),
                'directorPositions' => Position::whereHas('role', fn ($q) => $q->where('key', 'director'))->pluck('name', 'id')->toArray(),
                'data' => $user,
                'serial_pattern' => User::serialPattern(),
            ]);
    }

    public function edit(User $user)
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => route('users.update', $user),
                'method' => "PUT",
                'roles'  => Role::all()->pluck('name','id')->toArray(),
                'departments' => Department::all()->pluck('name', 'id')->toArray(),
                'companies' => Company::all()->pluck('name', 'id')->toArray(),
                'positions' => $user->getRelationValue('department')->positions()->pluck('name', 'id')->toArray(),
                'directorPositions' => Position::whereHas('role', fn ($q) => $q->where('key', 'director'))->pluck('name', 'id')->toArray(),
                'data' => $user,
                'serial_pattern' => User::serialPattern(),
            ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];

        if(is_null($request->get('password'))){
            unset($validated['password']);
        }else{
            $validated['password'] = \Hash::make($validated['password']);
        }

        if ($request->file('avatar')) {

            $avatar = $request->file('avatar');

            $validated['avatar'] = $avatar->storeAs('avatars', $avatar->hashName());

            if (Storage::exists($user->getAttribute('avatar'))) {
                Storage::delete($user->getAttribute('avatar'));
            }
        }

        $user->update($validated);

        // update user defaults
        $reversColumns = array_column($request->get('defaults') ?? [], 'value', 'parameter_id');
        $user->defaults()->sync(syncResolver($reversColumns,'value'));

        return back()->withNotify('info', $user->getAttribute('fullname'));
    }

    public function destroy(User $user)
    {
        if ($user->delete()) {
            if (Storage::exists($user->getAttribute('avatar'))) {
                Storage::delete($user->getAttribute('avatar'));
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function enable(User $user)
    {
        if ($user->update(['disabled_at' => null])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function disable(User $user)
    {
        if ($user->update(['disabled_at' => now()])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
