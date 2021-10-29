<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = RouteServiceProvider::DASHBOARD;

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'phoneUpdate']);
    }

    protected function authenticated(Request $request, $user)
    {
        $user->devices()->updateOrCreate(
            ['device_key' => $request->cookie('device_key')],
            [
                'device' => $request->userAgent(),
                'ip' => $request->ip(),
            ]
        );
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
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

    public function phoneUpdate(Request $request): RedirectResponse
    {
        $user = $request->user();

        if($user->getAttribute('phone') != $request->get('phone')){
            $msg = "Telefon nömrəniz dəyişdirildi. Döğrulama kodu yeni nömrəyə göndərildi.";
            $user->update(['phone' => $request->get('phone')]);
            $user->sendPhoneVerificationNotification();
        }else{
            $msg = "Artıq bu nömrəyə Döğrulama kodu göndərilib, xahiş edirik mesajları yoxlayasınız.";
        }

        return back()->withNotify("info", $msg, true);
    }
}
