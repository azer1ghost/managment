<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
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

        return view('panel.pages.users.index')
            ->with([
                'users' => User::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%".ucfirst($search)."%"))
                    ->simplePaginate(10)
            ]);
    }

    public function create()
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => route('users.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        if ($request->file('avatar')) {

            $avatar = $request->file('avatar');

            $validated['avatar'] = $avatar->storeAs('avatars', $avatar->hashName());
        }

        $user = User::create($validated);

        return redirect()
            ->route('users.index')
            ->withNotify('success', $user->getAttribute('name'));
    }

    public function show(User $user)
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $user
            ]);
    }

    public function edit(User $user)
    {
        return view('panel.pages.users.edit')
            ->with([
                'action' => route('users.update', $user),
                'method' => "PUT",
                'data' => $user
            ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->file('avatar')) {

            $avatar = $request->file('avatar');

            $validated['avatar'] = $avatar->storeAs('avatars', $avatar->hashName());

            if (Storage::exists($user->getAttribute('avatar'))) {
                Storage::delete($user->getAttribute('avatar'));
            }
        }

        $user->update($validated);

        return back()->withNotify('info', $user->getAttribute('name'));
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
}
