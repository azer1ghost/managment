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

    private function checkHasVerification($request)
    {
        if($request->user()->hasVerifiedPhone()){
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : abort(403);
        }
    }

    public function show(Request $request)
    {
        $this->checkHasVerification($request);

        if (!$request->user()->notifications()->where('type', '=','App\Notifications\Auth\VerifyPhone')->exists())
        {
            $request->user()->sendPhoneVerificationNotification();
        }

        return view('auth.verify-phone');
    }


    public function verify(Request $request)
    {
        $this->checkHasVerification($request);

        $request->validate([
            'code' => 'integer|digits:6'
        ]);

        $user = $request->user();

        $userLastVerifyNotification = $user->notifications()
            ->where('type', 'App\Notifications\Auth\VerifyPhone')
            ->latest()
            ->first('created_at')
            ->getAttribute('created_at');

        if ($userLastVerifyNotification->addMinutes(5) < now()){
            return back()->withErrors(['code' => 'Activation code has expired']);
        }

        if($user->getAttribute('verify_code') == $request->get('code')){
            if ($user->markPhoneAsVerified()) {
                event(new Verified($user));
            }
        } else {
            return back()->withErrors(['code' => 'Invalid activation code']);
        }

        if ($user->hasVerifiedPhone()) {
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
        $this->checkHasVerification($request);

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
        Route::get('phone/verify',  [PhoneVerifycationController::class, 'show'])->name('phone.verification.notice');
        Route::post('phone/verify', [PhoneVerifycationController::class, 'verify'])->name('phone.verification.verify');
        Route::post('phone/resend', [PhoneVerifycationController::class, 'resend'])->name('phone.verification.resend');
    }
}