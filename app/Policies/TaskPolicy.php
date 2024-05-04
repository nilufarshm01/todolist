<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Http\Controllers\TaskController;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }
    /**
     * Determine whether the user can view the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
    /**
     * Determine whether the user can create tasks.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }
    /**
     * Determine whether the user can update the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
    /**
     * Determine whether the user can save the task.
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function store(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

}
