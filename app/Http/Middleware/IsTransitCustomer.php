<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsTransitCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->isTransitCustomer()){
            return redirect()->route('service');
        }

        return $next($request);
    }
}
