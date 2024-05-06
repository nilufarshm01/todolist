<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $oneWeekAgo = Carbon::now()->subWeek();
        $status = $request->input('status', TaskStatus::All);
        $paginate = $request->input('paginate');

        $tasksQuery = Task::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($status === TaskStatus::Done) {
            $tasksQuery->where('status', TaskStatus::Done)->where('created_at', '>=', $oneWeekAgo);
        } elseif ($status === TaskStatus::InProgress) {
            $tasksQuery->where('status', '!=', TaskStatus::Done);
        }

        $tasks = $tasksQuery->paginate($paginate);

        return view('task.showTask', compact('tasks', 'status', 'paginate'));
    }
    public function create(): View
    {
        return view('task.addTask');
    }
    public function store(TaskRequest $request): RedirectResponse
    {
        $task = new Task();
        $task->user_id = auth()->id();
        $task->title = $request->input('task_title');
        $task->description = $request->input('task_desc');
        $task->status = $request->input('task_stat');

        if (Gate::allows('store', $task)) {
            $task->save();
            return redirect()->back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function show($title): View
    {
        $userId = Auth::id();
        $task = Task::where('title', $title)->where('user_id', $userId)->firstOrFail();

        if (Gate::allows('show', $task)) {
            return view('task.findTask', compact('task'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function edit($title): View
    {
        $userId = Auth::id();
        $task = Task::where('title', $title)->where('user_id', $userId)->firstOrFail();

        if (Gate::allows('edit', $task)) {
            return view('task.updateTask', compact('task'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function update(UpdateRequest $request, $title): RedirectResponse
    {
        $task = Task::where('title', $title)->firstOrFail();

        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->status = $request->input('status');

        if (Gate::allows('update', $task)) {
            $task->save();
            return redirect()->route('tasks.edit', ['task' => $task->title])->with('success', 'Task updated successfully.');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
