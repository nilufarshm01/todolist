<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Enum\TaskStatus;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(IndexTaskRequest $request): View
    {
        $userId = auth()->id();
        $status = $request->input('status', TaskStatus::incomplete->value);
        $perPage = $request->input('perPage');

        $tasksQuery = Task::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($status === TaskStatus::complete->value) {
            $tasksQuery->complete();
        } elseif ($status === TaskStatus::incomplete->value) {
            $tasksQuery->incomplete();
        } else {
            $tasksQuery->completeWithinLastWeek();
        }
        $tasks = $tasksQuery->paginate($perPage);

        return view('task.showTask', compact('tasks', 'status', 'perPage'));
    }
    public function create(): View
    {
        return view('task.addTask');
    }
    public function store(TaskRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $task = new Task();
        $task->user_id = Auth::id();
        $task->title = $validatedData['task_title'];
        $task->description = $validatedData['task_description'];
        $task->status = $validatedData['task_status'];
        $task->save();

        Gate::authorize('store', $task);

        return redirect()->back();
    }
    public function show(Task $task): View
    {
        Gate::authorize('show', $task);
        return view('task.findTask', ['task' => $task]);
    }
    public function edit(Task $task): View
    {
        Gate::authorize('show', $task);
        return view('task.updateTask', ['task' => $task]);
    }
    public function update(UpdateRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $validatedData = $request->validated();

        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->status = $validatedData['status'];
        $task->save();

        return redirect()->route('tasks.edit', ['task' => $task])->with('success', __('messages.task_updated_successfully'));
    }
}
