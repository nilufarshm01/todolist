<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $oneWeekAgo = Carbon::now()->subWeek();
        $status = $request->input('status');
        $paginate = $request->input('paginate');

        if (!$status || $status === 'Select') {
            return view('task.showTask', ['tasks' => []]);
        } elseif ($status === 'All') {
            $tasks = Task::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate($paginate);
        } elseif ($status === TaskStatus::Done->value) {
            $tasks = Task::where('user_id', $userId)->where('status', 'Done')->where('created_at', '>=', $oneWeekAgo)->orderBy('created_at', 'desc')->paginate($paginate);
        } elseif ($status === 'not_Done') {
            $tasks = Task::where('user_id', $userId)->where('status', '!=', 'Done')->orderBy('created_at', 'desc')->paginate($paginate);
        }


        return view('task.showTask', compact('tasks', 'status', 'paginate'));
    }

    // to add new Task(s)
    public function create(): View
    {
        return view('task.addTask');
    }

    public function store(TaskRequest $request): RedirectResponse
    {

//        Gate::define('show', function (User $user, Task $task) {
//            return $user->id === $task->user_id;
//        });

        $task = new Task();
        $task->user_id = auth()->id();
        $task->Title = $request->input('TaskName');
        $task->Description = $request->input('TaskDesc');
        $task->Status = $request->input('TaskStatus');
        $task->save();

        return redirect()->back();
    }

    public function show($title): View
    {
        $userId = Auth::id();
        $task = Task::where('Title', $title)->where('user_id', $userId)->firstOrFail();

//        Gate::define('show', function (User $user, Task $task) {
//            return $user->id === $task->user_id;
//        });

        return view('task.findTask', compact('task'));
    }

    // to update a saved Task
    public function edit($title): View
    {
        $userId = Auth::id();
        $task = Task::where('Title', $title)->where('user_id', $userId)->firstOrFail();

        return view('task.updateTask', compact('task'));
    }

    public function update(UpdateRequest $request, $title): RedirectResponse
    {
        $task = Task::where('Title', $title)->firstOrFail();

        Gate::define('update', function (User $user, Task $task) {
            return $user->id === $task->user_id;
        });

        $task->Title = $request->input('title');
        $task->Description = $request->input('description');
        $task->Status = $request->input('status');
        $task->save();

        return redirect()->route('tasks.edit', ['task' => $task->Title])->with('success', 'Task updated successfully.');

    }
}
