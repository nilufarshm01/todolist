<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use App\Models\User;


class AuthenticateUserTasks
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Retrieve the authenticated user's ID
        $userId = auth()->id();

        // Retrieve the tasks associated with the authenticated user
        $userTasks = Task::where('user_id', $userId)->pluck('id')->toArray();

        // Check if the requested task ID belongs to the authenticated user
        $taskId = $request->route('id');
        if (!in_array($taskId, $userTasks)) {
            abort(403, 'Unauthorized'); // Return 403 Forbidden if the user does not own the task
        }

        return $next($request);
    }
}
