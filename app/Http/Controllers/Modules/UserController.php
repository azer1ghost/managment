<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Traits\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use Permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $userCount = User::whereNull('disabled_at')->count();
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);
        $company  = $request->get('company');
        $department  = $request->get('department');
        $type  = $request->get('type') ?? 1;
        $status  = $request->get('status') ?? 1;

        return view('pages.users.index')
            ->with([
                'users' => User::with('company', 'department', 'role')
                    ->when($search,
                        fn ($query) => $query->where('name', 'like', "%".$search."%")
                                             ->orWhere('surname', 'like', "%".$search."%")
                                             ->orWhere('fin', 'like', "%".$search."%")
                                             ->orWhere('phone', 'like', "%".$search."%")
                                             ->orWhere('phone_coop', 'like', "%".$search."%")
                                             ->orWhere('id', $search))
                    ->when($company, fn ($query) => $query->where('company_id', $company))
                    ->when($department, fn ($query) => $query->where('department_id', $department))
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
                    ->when($status, function ($query, $status){
                        switch ($status){
                            case 1:
                                $query->whereNull('disabled_at');
                                break;
                            case 2:
                                $query->whereNotNull('disabled_at');
                                break;
                        }
                    })
                    ->orderByDesc('order')
                    ->paginate($limit),
                'companies' => Company::get(['id', 'name']),
                'departments' => Department::get(['id', 'name']),
                'types' => User::types(),
                'statuses' => User::status(),
                'user_count' => $userCount
            ]);
    }

    public function create()
    {
        return view('pages.users.edit')
            ->with([
                'action' => route('users.store'),
                'method' => 'POST',
                'data' => new User(),
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

        $this->permissions($validated, new User());

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
        return view('pages.users.edit')
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
        return view('pages.users.edit')
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

        $this->permissions($validated, $user);

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

    public function loginAsUser(User $user)
    {
        $previousId = auth()->id();
        Auth::login($user);

        if($user->isDeveloper()){
            return redirect()->route('users.index')->withCookie(Cookie::forget('user_id'));
        }

        return redirect()->route('dashboard')->withCookie('user_id', $previousId);

    }
}
