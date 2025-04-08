<?php

namespace App\Providers;

use App\Repositories\AssistantRepository;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AssistantRepositoryInterface::class, AssistantRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
