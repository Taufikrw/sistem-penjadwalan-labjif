<?php

namespace App\Providers;

use App\Repositories\AssistantRepository;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\CourseScheduleRepository;
use App\Repositories\PracticumRepository;
use App\Repositories\ScheduleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AssistantRepositoryInterface::class, AssistantRepository::class);
        $this->app->singleton(PracticumRepositoryInterface::class, PracticumRepository::class);
        $this->app->singleton(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->singleton(CourseScheduleRepositoryInterface::class, CourseScheduleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
