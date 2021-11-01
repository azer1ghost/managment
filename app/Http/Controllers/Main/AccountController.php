<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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
            'companies' => Company::all()->pluck('name', 'id')->toArray(),
            'positions' =>  auth()->user()->getRelationValue('department')->positions()->pluck('name', 'id')->toArray(),
            'serial_pattern' => User::serialPattern(),
        ]);
    }

    public function save(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $currentRole = $request->user()->getRelationValue('role')->getAttribute('id');
        $currentCompany = $request->user()->getRelationValue('company')->getAttribute('id');
        $currentDepartment = $request->user()->getRelationValue('department')->getAttribute('id');

        // 1, 2 => Admin, President
        $validated['role_id']        =  !in_array($currentRole, array(1, 2)) ? $currentRole : $validated['role_id'];
        $validated['company_id']     =  !in_array($currentRole, array(1, 2)) ? $currentCompany : $validated['company_id'];
        $validated['department_id']  =  !in_array($currentRole, array(1, 2)) ? $currentDepartment : $validated['department_id'];

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
}
