<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    protected string $redirectTo = RouteServiceProvider::ACCOUNT;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showPartnersRegistrationForm()
    {
        return view('auth.register-partners');
    }

    public function register(Request $request)
    {
        $this->validator($request)->validate();

        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function validator(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => ['filled', 'string', 'max:50'],
            'surname' => ['filled', 'string', 'max:50'],
            'phone' => ['filled', 'string', 'min:10', 'max:15', 'unique:users,phone'],
            'phone_coop' => ['filled', 'string', 'min:10', 'max:15', 'unique:users,phone_coop'],
            'email_coop' => ['filled', 'allowed_domain','string', 'email:rfc,dns', 'max:50', 'unique:users,email_coop'],
            'email' => ['filled', 'string', 'email:rfc,dns', 'max:50', 'unique:users,email'],
            'department_id' => ['filled', 'integer', 'min:1'],
            'company_id' => ['filled', 'integer', 'min:1'],
            'password' => ['filled', 'string', 'min:8', 'confirmed'],
            'default_lang' => ['filled', 'string'],
            'avatar'  => ['nullable','sometimes', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'serial' => ['filled', 'string'],
            'fin' => ['filled', 'string', 'min:7', 'max:7'],
            'is_partner' => ['filled', 'boolean']
        ]);

        if($request->expectsJson()){
            if ($validator->passes()) {
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        return $validator;
    }

    protected function create(Request $request): User
    {
        $data = $request->all();

        if($avatar = $request->file('avatar')){
            $data['avatar'] = $avatar->storeAs('avatars', $avatar->hashName());
        }else{
            $data['avatar'] = null;
        }

        $data['is_partner']  = $request->has('is_partner');
        $data['role_id'] = $request->has('is_partner') ? 8 : 4;
        $data['verify_code'] = rand(111111, 999999);
        $data['password'] = Hash::make($data['password']);
        
        return User::create($data);
    }
}
