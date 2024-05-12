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
use App\Models\User; // todo: remove unused import
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $oneWeekAgo = Carbon::now()->subWeek();
        $status = $request->input('status', TaskStatus::All); // todo : there shouldn't be status with name of all.
        $paginate = $request->input('paginate');

        $tasksQuery = Task::where('user_id', $userId)->orderBy('created_at', 'desc'); // todo : use scopes in your laravel queries: https://laravel.com/docs/11.x/eloquent#local-scopes:~:text=%2D%3Eget()%3B-,Local%20Scopes,-Local%20scopes%20allow
        if ($status === TaskStatus::Done) {
            $tasksQuery->where('status', TaskStatus::Done)->where('created_at', '>=', $oneWeekAgo); // todo : use scopes in your laravel queries: https://laravel.com/docs/11.x/eloquent#local-scopes:~:text=%2D%3Eget()%3B-,Local%20Scopes,-Local%20scopes%20allow
        } elseif ($status === TaskStatus::InProgress) {
            $tasksQuery->where('status', '!=', TaskStatus::Done); // todo : use scopes in your laravel queries: https://laravel.com/docs/11.x/eloquent#local-scopes:~:text=%2D%3Eget()%3B-,Local%20Scopes,-Local%20scopes%20allow
        }
        //todo : your logic doesn't result required business logic, for default we need completed tasks during 6 days ago with in complete tasks as a whole or either of the as requested.
        $tasks = $tasksQuery->paginate($paginate); //todo : there is a serious bug in your code, sometimes a string value is set to your $paginate. it is due to the fact that you are not using a validation in your index. apply it to your index method().
        //todo : $paginate variable name doesn't seem to be a proper name, change it for a better one.

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
        $task->status = $request->input('task_stat'); // todo : This approach is completely wrong, there is several problems in your logic.

        if (Gate::allows('store', $task)) { //todo : you're passing the task that u have passed id of current user to the uesr_id. So your policy would always return true.
            $task->save();
            return redirect()->back(); // todo : you should check other things with this policy. For instance, user has the permission to create any task or not !
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function show($title): View // todo: it is a better technique to use route model binding: https://laravel.com/docs/11.x/routing#route-model-binding
    {
        $task = Task::where('title', $title)->first();
        Gate::authorize('show', $task); // todo: you can also use this method. apply this method to other methods
        return view('task.findTask', compact('task'));

//        if (Gate::allows('show', $task)) {
//            return view('task.findTask', compact('task'));
//        } else {
//            abort(403, 'Unauthorized action.');
//        }
    }
    public function edit($title): View // todo: it is a better technique to use route model binding: https://laravel.com/docs/11.x/routing#route-model-binding
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

//        $task->title = $request->input('title');
//        $task->description = $request->input('description');
//        $task->status = $request->input('status');

        if (Gate::allows('update', $task)) { // todo : you are following a wrong approach in authorization, first retrieve the resource, check eligibility of your current user, then update it. The used method would be more appropriate.
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->save();
            return redirect()->route('tasks.edit', ['task' => $task->title])->with('success', 'Task updated successfully.'); //todo : use localization: https://laravel.com/docs/11.x/localization
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
