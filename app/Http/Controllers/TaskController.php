<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    //  to show, filter, and paginate Task(s)
    public function index(Request $request): View
    {
        $userId = auth()->id();

        $oneWeekAgo = Carbon::now()->subWeek();
        $status = $request->input('status');
        $paginate = $request->input('paginate');

        try {
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
        } catch (Exception $e) {
            return view('task.error')->with('error', 'An error occurred while processing your request.');
        }
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
            'TaskName' => ['required' , 'string' , 'max:255'],
            'TaskDesc' => ['nullable', 'string'],
            'TaskStatus' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ]);
    }

    // to save the new Task(s) in database
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->isValid_add($request);

            $task = new Task();
            $task->user_id = auth()->id();
            $task->Title = $request->input('TaskName');
            $task->Description = $request->input('TaskDesc');
            $task->Status = $request->input('TaskStatus');
            $task->save();

            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }

    // to show a specific Task's information
    public function show($title): View
    {
        try {
            $userId = Auth::id();
            $task = Task::where('Title', $title)->where('user_id', $userId)->firstOrFail();

            return view('task.findTask', compact('task'));
        } catch (Exception $e) {
            return view('task.error')->with('error', 'Task not found.');
        }
    }

    // to update a saved Task
    public function edit($title): View
    {
        try {
            $userId = Auth::id();
            $task = Task::where('Title', $title)
                ->where('user_id', $userId)->firstOrFail();

            return view('task.updateTask', compact('task'));
        } catch (Exception $e) {
            return view('task.error')->with('error', 'Task not found.');
        }
    }

    // to validate the updated data
    private function isValid_update(Request $request): void
    {
        $request->validate([
            'title' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ]);
    }

    // to save the updated parts
    public function update(Request $request, $title): RedirectResponse
    {
        try {
            $this->isValid_update($request);

            $task = Task::where('Title', $title)->firstOrFail();

            $task->Title = $request->input('title');
            $task->Description = $request->input('description');
            $task->Status = $request->input('status');
            $task->save();

            return redirect()->route('tasks.edit', ['task' => $task->Title])->with('success', 'Task updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('task.error', 'An error occurred while updating the task.');
        }
    }
}
