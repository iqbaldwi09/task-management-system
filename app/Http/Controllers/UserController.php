<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->userService->getAllUsers();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ];
                })
                ->make(true);
        }

        return view('users.index');
    }

    public function store(UserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return response()->json(['message' => 'User created successfully.']);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UserRequest $request, $id)
    {
        $this->userService->updateUser($id, $request->validated());
        return response()->json(['message' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully.']);
    }
}
