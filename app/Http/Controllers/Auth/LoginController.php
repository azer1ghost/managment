<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = RouteServiceProvider::DASHBOARD;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request): array
    {
        $username = $request->get('login');

        $field = in_array(explode('@', $username)[1], Company::pluck('website')->toArray()) ? 'email_coop' : 'email';

        return [
             $field    => $username,
            'password' => $request->get('password'),
        ];
    }

}
