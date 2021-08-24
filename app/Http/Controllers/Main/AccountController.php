<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\UserController;
use App\Http\Requests\UserRequest;
use App\Models\Company;
use App\Models\Department;
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

        UserController::saveDefaults($user, $request->get('defaults'));

        return back()->withNotify('info', $user->getAttribute('fullname'));
    }
}
