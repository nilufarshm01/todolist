<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Task;
use App\Policies\TaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
    ];
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::resource('task', TaskPolicy::class, [
            'store' => 'store',
            'view' => 'view',
            'edit' => 'edit',
            'update' => 'update',
        ]);
    }
}
