<?php

namespace App\Providers;

use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\LogisticsRepositoryInterface;
use App\Interfaces\WorkRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\LogisticsRepository;
use App\Repositories\WorkRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected array $repos = [
        ClientRepositoryInterface::class => ClientRepository::class,
        WorkRepositoryInterface::class => WorkRepository::class,
        LogisticsRepositoryInterface::class => LogisticsRepository::class
    ];

    public function register()
    {
        foreach ($this->repos as $interface => $repo) {
            $this->app->bind($interface, $repo);
        }
    }

    public function boot()
    {
        //
    }
}