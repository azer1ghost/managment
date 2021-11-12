<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDeactivated
{
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->isDisabled()){
            return redirect()->route('deactivated');
        }

        return $next($request);
    }
}
