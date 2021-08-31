<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Validator;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         Inquiry::class => InquiryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
//        $this->registerPolicies();

        Validator::extend('allowed_domain', function($attribute, $value, $parameters, $validator) {
            return in_array(explode('@', $value)[1], Company::select(['website'])->get()->toArray());
        }, 'This domain not valid for registration. Please use company domain');

        // only return viewAny permissions of user
        collect(config('auth.permissions'))
        ->filter(function ($permission){
            return stripos($permission, 'viewAny') ?: $permission;
        })
        ->map(function ($permission){
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->role->hasPermission($permission);
            });
        });

        Gate::define('generally', fn() => true);
    }
}
