<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    DesignContract,
    UserContract,
    CommentContract,
    TeamContract,
    InvitationContract,
    ChatContract,
    MessageContract
};
use App\Repositories\Eloquent\{
    DesignRepository,
    UserRepository,
    CommentRepository,
    TeamRepository,
    InvitationRepository,
    ChatRepository,
    MessageRepository
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
        $this->app->bind(CommentContract::class, CommentRepository::class);
        $this->app->bind(TeamContract::class, TeamRepository::class);
        $this->app->bind(InvitationContract::class, InvitationRepository::class);
        $this->app->bind(ChatContract::class, ChatRepository::class);
        $this->app->bind(MessageContract::class, MessageRepository::class);
    }
}
