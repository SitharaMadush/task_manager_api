<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Task Controller handles task-related HTTP requests.
 */
class TaskController extends Controller
{
    /**
     * The task service instance variable.
     *
     * @var TaskServiceInterface
     */
    protected $service;

    /**
     * Inject the TaskServiceInterface.
     *
     * @param TaskServiceInterface $service
     */
    public function __construct(TaskServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Returns a listing of the user's tasks, optionally filtered by status.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        try{
            $userId = Auth::id();
            $filters['status'] = $request->query('status');
            $tasks = $this->service->list($userId, $filters);
            return response()->json($tasks);
        }catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks.'], 500);
        }
    }

    /**
     * Store a new task.
     *
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->service->create([
                'title' => $request->title,
                'user_id' => Auth::id(),
            ]);
            return response()->json($task, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create task.'], 500);
        }
    }

    /**
     * Update the given task.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->service->update($id, ['title' => $request->title]);
            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update task.'], 500);
        }
    }

    /**
     * Mark the given task as deleted.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json(['message' => 'Deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete task.'], 500);
        }
    }

    /**
     * Mark the specified task as completed.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markComplete(int $id): JsonResponse
    {
        try {
            $task = $this->service->markComplete($id);
            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to complete task.'], 500);
        }
    }
}
