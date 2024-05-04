<?php

namespace App\Http;

use App\Http\Middleware\AuthenticateUserTasks;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Kernel class definition
    protected $routeMiddleware = [
        // Other middleware entries...

        'auth.tasks' => AuthenticateUserTasks::class,
    ];
}
