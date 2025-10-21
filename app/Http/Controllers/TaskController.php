<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    protected $taskService;
    protected $userService;

    public function __construct(TaskService $taskService, UserService $userService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $tasks = $this->taskService->getAllTasks();

            return DataTables::of($tasks)
                ->addColumn('user', fn($task) => $task->user->name ?? '-')
                ->addColumn('can_edit', fn($task) => $user->role === 'admin' || $task->user_id === $user->id)
                ->editColumn('status', fn($task) => $task->status)
                ->make(true);
        }

        $users = $this->userService->getAllUsers($user);

        return view('tasks.index', compact('users'));
    }

    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        if ($user->role !== 'admin') {
            $data['user_id'] = $user->id;
        }

        $this->taskService->createTask($data);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully'
        ]);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->taskService->getTaskById($id);

        if (auth()->user()->role !== 'admin' && $task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->taskService->updateTask($task, $request->validated());
        return response()->json(['success' => true, 'message' => 'Task updated successfully']);
    }

    public function destroy($id)
    {
        $task = $this->taskService->getTaskById($id);

        $this->taskService->deleteTask($task);
        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }
}
