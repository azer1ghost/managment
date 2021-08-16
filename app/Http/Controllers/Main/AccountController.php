<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
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
        return view('panel.pages.main.account');
    }

    public function save(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = is_null($validated['password']) ? auth()->user()->getAuthPassword() : Hash::make($validated['password']);

        $user->update($validated);

        return back()->withNotify('info', $user->getAttribute('fullname'));
    }
}
