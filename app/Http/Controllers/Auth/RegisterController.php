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
        return view('auth.register')->with([
            'serial_pattern' => User::serialPattern(),
        ]);
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
        $validated = $request->all();

        if(array_key_exists('phone', $validated)){
            $validated['phone'] = phone_cleaner($validated['phone']);
        }
        if(array_key_exists('phone_coop', $validated)){
            $validated['phone_coop'] = phone_cleaner($validated['phone_coop']);
        }

        $validator =  Validator::make($validated, [
            'name' => ['filled', 'string', 'max:50'],
            'surname' => ['filled', 'string', 'max:50'],
            'phone' => ['filled', 'string', 'min:9', 'max:15', 'unique:users,phone'],
            'phone_coop' => ['filled', 'string', 'min:9', 'max:15', 'unique:users,phone_coop'],
            'email_coop' => ['filled', 'allowed_domain','string', 'email:rfc,dns', 'max:50', 'unique:users,email_coop'],
            'email' => ['filled', 'string', 'email:rfc,dns', 'max:50', 'unique:users,email'],
            'department_id' => ['filled', 'integer', 'min:1'],
            'company_id' => ['filled', 'integer', 'min:1'],
            'password' => ['filled', 'string', 'min:8', 'confirmed'],
            'default_lang' => ['filled', 'string'],
            'avatar'  => ['nullable','sometimes', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'serial' => ['filled', 'string'],
            'fin' => ['filled', 'string', 'min:7', 'max:7', 'unique:users,fin'],
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

        $data['name'] = ucfirst($data['name']);
        $data['surname'] = ucfirst($data['surname']);
        $data['fin'] = strtoupper($data['fin']);
        $data['is_partner']  = $request->has('is_partner');
        $data['role_id'] = $request->has('is_partner') ? 8 : 4;
        $data['verify_code'] = rand(111111, 999999);
        $data['password'] = Hash::make($data['password']);
        
        return User::create($data);
    }

    protected function createTransitUser(Request $request): User
    {
        $data = $request->all();
        $data['name'] = ucfirst($data['name']);
        $data['role_id'] = User::TRANSIT;
        $data['verify_code'] = rand(111111, 999999);
        $data['password'] = Hash::make($data['password']);
        
        // Handle rekvizit file upload if exists
        if($rekvisit = $request->file('rekvisit')){
            $data['rekvisit'] = $rekvisit->storeAs('rekvizits', $rekvisit->hashName());
        } else {
            $data['rekvisit'] = null;
        }

        return User::create($data);
    }

    public function transitRegister(Request $request)
    {
        $this->transitValidator($request)->validate();

        event(new Registered($user = $this->createTransitUser($request)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
    
    protected function transitValidator(Request $request)
    {
        $validated = $request->all();

        if(array_key_exists('phone', $validated)){
            $validated['phone'] = phone_cleaner($validated['phone']);
        }

        $validator = Validator::make($validated, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:9', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['nullable', 'string', 'in:legal,people'],
            'country' => ['nullable', 'string', 'max:255'],
            'voen' => ['nullable', 'string', 'max:255'],
            'rekvisit' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ]);

        if($request->expectsJson()){
            if ($validator->passes()) {
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        return $validator;
    }

}
