<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->getAttribute('default_lang')) {
            App::setlocale($request->user()->getAttribute('default_lang'));
        }else if (session()->has('locale')){
            App::setlocale(session()->get('locale'));
        }
        return $next($request);
    }

    public static function locale(string $locale): void
    {
        if (!array_key_exists($locale, config('app.locales')))
        {
            abort(403);
        }

        App::setlocale($locale);
        session()->put('locale', $locale);
    }
}