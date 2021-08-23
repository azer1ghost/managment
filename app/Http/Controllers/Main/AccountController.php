<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use App\Models\Social;
use App\Models\UserDefault;
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
        return view('panel.pages.main.account',[
            'roles' => Role::all()->pluck('name','id')->toArray(),
            'departments' => Department::all()->pluck('name', 'id')->toArray(),
            'companies' => Company::all()->pluck('name', 'id')->toArray()
]);
    }

    public function save(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
//        dd($validated);
        $currentRole = $request->user()->getRelationValue('role')->getAttribute('id');
        $currentCompany = $request->user()->getRelationValue('company')->getAttribute('id');
        $currentDepartment = $request->user()->getRelationValue('department')->getAttribute('id');
        $validated['password'] = is_null($validated['password']) ? auth()->user()->getAuthPassword() : Hash::make($validated['password']);

        // 1, 2 => Admin, President
        $validated['role_id']        =  !in_array($currentRole, array(1, 2)) ? $currentRole : $validated['role_id'];
        $validated['company_id']     =  !in_array($currentRole, array(1, 2)) ? $currentCompany : $validated['company_id'];
        $validated['department_id']  =  !in_array($currentRole, array(1, 2)) ? $currentDepartment : $validated['department_id'];

        $user->update($validated);

        // Add, update or delete social networks
        $defaults = collect($request->get('defaults') ?? []);

        // destroy should appear before create or update
        UserDefault::destroy($user->defaults()->pluck('id')->diff($defaults->pluck('id')));

        $defaults->each(fn($default) => $user->defaults()->updateOrCreate(['id' => $default['id']], $default));

        return back()->withNotify('info', $user->getAttribute('fullname'));
    }
}
