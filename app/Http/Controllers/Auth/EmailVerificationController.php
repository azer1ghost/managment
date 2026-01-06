<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Route;

class EmailVerificationController extends Controller
{
    use RedirectsUsers;

    protected string $redirectTo = RouteServiceProvider::SERVICE;

    public function __construct()
    {
        $this->middleware('auth:transit');
        $this->middleware('throttle:3,1')->only('verify');
        $this->middleware('throttle:1,1')->only('resend');
    }

    private function checkHasVerification($request)
    {
        if($request->user('transit')->hasVerifiedEmail()){
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : abort(403);
        }
    }

    public function show(Request $request)
    {
        $this->checkHasVerification($request);

        if (!$request->user('transit')->notifications()->where('type', '=','App\Notifications\Auth\VerifyEmail')->exists())
        {
            $request->user('transit')->sendEmailVerificationNotification();
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $this->checkHasVerification($request);

        $request->validate([
            'code' => 'integer|digits:6'
        ]);

        $user = $request->user('transit');

        $userLastVerifyNotification = $user->notifications()
            ->where('type', 'App\Notifications\Auth\VerifyEmail')
            ->latest()
            ->first();

        // Əgər hələ heç bir doğrulama notification-u yaranmayıbsa,
        // müddət yoxlamasını keçirik (yalnız kod uyğunluğunu yoxlayırıq)
        if ($userLastVerifyNotification) {
            $createdAt = $userLastVerifyNotification->getAttribute('created_at');

            if ($createdAt->addMinutes(5) < now()){
                return back()->withErrors(['code' => 'Doğrulama kodu müddəti bitib']);
            }
        }

        if($user->getAttribute('verify_code') == $request->get('code')){
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        } else {
            return back()->withErrors(['code' => 'Yanlış doğrulama kodu']);
        }

        if ($user->hasVerifiedEmail()) {
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

        $request->user('transit')->updateEmailVerificationCode();

        $request->user('transit')->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : back()->with('resent', true);
    }

    public static function routes()
    {
        Route::get('/email/verification', [self::class, 'show'])->name('email.verification.notice');
        Route::post('/email/verification', [self::class, 'verify'])->name('email.verification.verify');
        Route::post('/email/verification/resend', [self::class, 'resend'])->name('email.verification.resend');
    }
}

