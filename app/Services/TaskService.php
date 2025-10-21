<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks()
    {
        return $this->taskRepository->getAllTasks();
    }
    
    public function getTaskById($id)
    {
        return $this->taskRepository->getById($id);
    }

    public function createTask(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask($task, array $data)
    {
        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask($task)
    {
        return $this->taskRepository->delete($task);
    }
}
