<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RegisterDevice
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->hasCookie('device_key')) {
            return $next($request);
        }

        return $next($request)->withCookie(cookie()->forever('device_key', $this->unique_code(9)));
    }

    function unique_code($limit): string
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}