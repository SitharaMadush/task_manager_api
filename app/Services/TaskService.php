<?php

namespace App\Services;

use App\Helpers\ErrorLogger;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\TaskServiceInterface;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * This service class handles business logic related to tasks
 */

class TaskService implements TaskServiceInterface
{

    protected $repository;

    /**
     * Constructor to inject TaskRepository dependency.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns a list of tasks based on filters and user ID.
     *
     * @param array $filters
     * @param int $userId
     * @return \Illuminate\Support\Collection
     *
     * @throws Exception
     */
    public function list(int $userId, array $filters): Collection
    {
        try{
            return $this->repository->getUserTasks($userId, $filters);
        }catch (Exception $e) {
            // Log to Laravel log
            ErrorLogger::log('Failed to fetch tasks.', $e);
            throw $e;
        }
    }

    /**
     * Create a new task with the provided data.
     *
     * @param array $data
     * @return \App\Models\Task
     *
     * @throws Exception
     */
    public function create(array $data): Task
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            // Log to Laravel log
            ErrorLogger::log('Failed to create task.', $e);
            throw $e;
        }
    }

    /**
     * Update an existing task by ID with new data.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Task
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $data): Task
    {
        try {
            $task = $this->repository->findById($id);
            return $this->repository->update($task->id, $data);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            // Log to Laravel log
            ErrorLogger::log('Failed to update task.', $e);
            throw $e;
        }
    }

    /**
     * Delete a task by its ID.
     *
     * @param int $id
     * @return bool
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            $task = $this->repository->findById($id);
            return $this->repository->delete($task->id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            // Log to Laravel log
            ErrorLogger::log('Failed to delete task.', $e);
            throw $e;
        }
    }

    /**
     * Mark a task as completed by its ID.
     *
     * @param int $id
     * @return \App\Models\Task
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function markComplete(int $id): Task
    {
        try {
            $task = $this->repository->findById($id);
            return $this->repository->update($task->id, ['completed' => true]);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            // Log to Laravel log
            ErrorLogger::log('Failed to mark task as complete.', $e);
            throw $e;
        }
    }
}