<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function account(): View
    {
        return view('panel.pages.main.account',['roles' => Role::all()->pluck('name','id')->toArray(),
]);
    }

    public function save(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $currentRole = $request->user()->getRelationValue('role')->getAttribute('id');
        $validated['password'] = is_null($validated['password']) ? auth()->user()->getAuthPassword() : Hash::make($validated['password']);
        // 1, 2 => Admin, President
        $validated['role_id']  = !in_array($currentRole, array(1, 2)) ? $currentRole : $validated['role_id'];
        $user->update($validated);

        return back()->withNotify('info', $user->getAttribute('fullname'));
    }
}
