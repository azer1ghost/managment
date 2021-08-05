<?php

namespace App\Providers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Bind new method
 * @method with($info)
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::componentNamespace('App\\View\\Components\\Bread\\Input', 'input');

        RedirectResponse::macro('withNotify', function ($type, $message = null){
            return $this->with(notify()->$type($message));
        });

        Paginator::useBootstrap();
    }
}
