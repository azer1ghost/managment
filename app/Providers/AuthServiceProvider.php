<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    protected $permissions;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->permissions = collect(config('auth.permissions'));
    }
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
//    protected $policies = [
//         'App\Models\Model' => 'App\Policies\ModelPolicy',
//    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
       // $this->registerPolicies();

        $this->permissions->map(function ($permission){
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        });

    }
}
