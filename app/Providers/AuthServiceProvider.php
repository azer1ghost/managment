<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;
use App\Policies\DatabaseNotificationPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Validator;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        DatabaseNotification::class => DatabaseNotificationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Validator::extend('allowed_domain', function($attribute, $value, $parameters, $validator) {
            return in_array(explode('@', $value)[1], Company::pluck('website')->toArray());
        }, 'This domain not valid for registration. Please use company domain');

        // only return viewAny permissions of user
        collect(config('auth.permissions'))
        ->filter(function ($permission){
            return stripos($permission, 'viewAny') === 0;
        })
        ->map(function ($permission){
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        });

        Gate::define('generally', fn() => true);
        Gate::define('signature', fn() => true);
        Gate::define('structure', fn() => true);
    }
}
