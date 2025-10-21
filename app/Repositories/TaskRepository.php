<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAllTasks()
    {
        return Task::with('user')->latest();
    }

    public function getById($id)
    {
        return Task::findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }
}
