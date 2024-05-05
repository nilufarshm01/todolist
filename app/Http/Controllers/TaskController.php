<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    //  to show, filter, and paginate Task(s)
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
        } elseif ($status === 'Done') {
            $tasks = Task::where('user_id', $userId)->where('status', 'Done')->where('created_at', '>=', $oneWeekAgo)->orderBy('created_at', 'desc')->paginate($paginate);
        } elseif ($status === 'not_Done') {
            $tasks = Task::where('user_id', $userId)->where('status', '!=', 'Done')->orderBy('created_at', 'desc')->paginate($paginate);
        }

        if ($tasks instanceof LengthAwarePaginator) {
            $tasks->appends(request()->except('page'));
        }

        return view('task.showTask', compact('tasks', 'status', 'paginate'));
    }

    // to add new Task(s)
    public function create(): View
    {
        return view('task.addTask');
    }

    // to validate the new Task(s)
    private function isValid_add(Request $request): void
    {
        $request->validate([
            'TaskName' => ['required', 'string', 'max:255'],
            'TaskDesc' => ['nullable', 'string'],
            'TaskStatus' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ]);
    }

    // to save the new Task(s) in database
    public function store(TaskRequest $request): RedirectResponse
    {
        $this->isValid_add($request);

        Gate::define('show', function (User $user, Task $task) {
            return $user->id === $task->user_id;
        });

        $task = new Task();
        $task->user_id = auth()->id();
        $task->Title = $request->input('TaskName');
        $task->Description = $request->input('TaskDesc');
        $task->Status = $request->input('TaskStatus');
        $task->save();

        return redirect()->back();
    }

    // to show a specific Task's information
    public function show($title): View
    {
        $userId = Auth::id();
        $task = Task::where('Title', $title)->where('user_id', $userId)->firstOrFail();

        Gate::define('show', function (User $user, Task $task) {
            return $user->id === $task->user_id;
        });

        return view('task.findTask', compact('task'));
    }

    // to update a saved Task
    public function edit($title): View
    {
        $userId = Auth::id();
        $task = Task::where('Title', $title)->where('user_id', $userId)->firstOrFail();

        return view('task.updateTask', compact('task'));
    }

    // to validate the updated data
    private function isValid_update(Request $request): void
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ]);
    }

    // to save the updated parts
    public function update(UpdateRequest $request, $title): RedirectResponse
    {
        $this->isValid_update($request);

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
