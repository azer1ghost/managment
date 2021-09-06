<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\MustVerifyPhone;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsurePhoneVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyPhone &&
            ! $request->user()->hasVerifiedPhone())) {
            return $request->expectsJson()
                    ? abort(403, 'Your account is not verified.')
                    : Redirect::guest(URL::route($redirectToRoute ?: 'phone.verification.notice'));
        }

        return $next($request);
    }
}
