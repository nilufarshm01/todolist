<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Task;
use App\Policies\TaskPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
