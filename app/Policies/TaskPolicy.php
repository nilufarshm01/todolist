<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Http\Controllers\TaskController;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function store(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
    public function show(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
    public function edit(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
