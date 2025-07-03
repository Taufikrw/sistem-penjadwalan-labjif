<?php

namespace App\Providers;

use App\Repositories\AssistantRepository;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Repositories\Contracts\LaboratoriumRepositoryInterface;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\CourseScheduleRepository;
use App\Repositories\LaboratoriumRepository;
use App\Repositories\PracticumRepository;
use App\Repositories\ScheduleRepository;
use App\View\Components\DataTable;
use Illuminate\Support\Facades\Blade;
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
        $this->app->singleton(LaboratoriumRepositoryInterface::class, LaboratoriumRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('data-table', DataTable::class);
    }
}
