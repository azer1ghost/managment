<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Route;

class PhoneVerifycationController extends Controller
{
    use RedirectsUsers;

    protected string $redirectTo = RouteServiceProvider::DASHBOARD;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:3,1')->only('verify');
        $this->middleware('throttle:1,1')->only( 'resend');
    }

    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedPhone())
        {
            redirect($this->redirectPath());
        }

        if (!$request->user()->notifications()->where('type', '=','App\Notifications\Auth\VerifyPhone')->exists())
        {
            $request->user()->sendPhoneVerificationNotification();
        }

        return view('auth.verify-phone');
    }


    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'integer|digits:6'
        ]);

        if($request->user()->getAttribute('verify_code') == $request->get('code')){
            if ($request->user()->markPhoneAsVerified()) {
                event(new Verified($request->user()));
            }
        } else {
            return back()->withErrors(['code' => 'Invalid activation code']);
        }

        if ($request->user()->hasVerifiedPhone()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect($this->redirectPath())->with('verified', true);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedPhone()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        $request->user()->updatePhoneVerificationCode();

        $request->user()->sendPhoneVerificationNotification();

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : back()->with('resent', true);
    }

    protected function verificationCode($notifiable): string
    {
        if (!$notifiable->hasVerifiedPhone())
        {
            $notifiable->updatePhoneVerificationCode();
        }

        return $notifiable->verify_code;
    }

    public static function routes()
    {
        Route::get('phone/verify', [PhoneVerifycationController::class, 'show'])->name('phone.verification.notice');
        Route::post('phone/verify', [PhoneVerifycationController::class, 'verify'])->name('phone.verification.verify');
        Route::post('phone/resend', [PhoneVerifycationController::class, 'resend'])->name('phone.verification.resend');
    }
}