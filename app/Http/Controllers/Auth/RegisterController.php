<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $data['phone'] = phone_cleaner($data['phone']);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'email_coop' => ['required', 'allowed_domain','string', 'email:rfc,dns', 'max:50', 'unique:users,email_coop'],
            'department_id' => ['required', 'integer', 'min:1'],
            'company_id' => ['required', 'integer', 'min:1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => null,
            'email_coop' => $data['email_coop'],
            'phone' => $data['phone'],
            'role_id' => 4,
            'department_id' => $data['department_id'],
            'company_id' => $data['company_id'],
            'password' => Hash::make($data['password']),
            'verify_code' => rand(111111, 999999)
        ]);
    }
}
