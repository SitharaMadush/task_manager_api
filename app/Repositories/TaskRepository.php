<?php
namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * This Repository class manages data access logic related to tasks.
 */
class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    /**
     * Constructor to inject the Task model dependency.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * Get tasks for a specific user with optional filters.
     *
     * @param array $filters Array of filter options (e.g. status).
     * @param int $userId The ID of the user whose tasks to retrieve.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserTasks(array $filters = [], int $userId): Collection
    {
        $query = $this->model->where('user_id', $userId);

        if (isset($filters['status'])) {
            if ($filters['status'] == config('constants.STATUSES.STATUS_COMPLETED')) {
                $query->where('completed', true);
            } elseif ($filters['status'] == config('constants.STATUSES.STATUS_PENDING')) {
                $query->where('completed', false);
            }
        }

        return $query->latest()->get();
    }

    /**
     * Create a new task with the given data.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    /**
     * Find a task by its ID and ensure it belongs to the authenticated user.
     *
     * @param int $id
     * @return Task
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): Task
    {
        return $this->model->where('user_id', Auth::id())->findOrFail($id);
    }

    /**
     * Update a task by its ID with new data.
     *
     * @param int $id
     * @param array $data
     * @return Task
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $id, array $data): Task
    {
        $task = $this->model->findOrFail($id);
        $task->update($data);
        return $task;
    }

    /**
     * Delete a task by its ID.
     *
     * @param int $id
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $task = $this->model->findOrFail($id);
        return $task->delete();
    }
}