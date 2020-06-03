<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    DesignContract,
    UserContract
};
use App\Repositories\Eloquent\{
    DesignRepository,
    UserRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         *  binding the contract with its concrete implementation
         */
        $this->app->bind(DesignContract::class, DesignRepository::class);
        $this->app->bind(UserContract::class, UserRepository::class);
    }
}
