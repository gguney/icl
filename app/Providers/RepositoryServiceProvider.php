<?php

namespace App\Providers;

use App\Repositories\FixtureRepository;
use App\Repositories\Interfaces\FixtureRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Repositories\TeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(FixtureRepositoryInterface::class, FixtureRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
