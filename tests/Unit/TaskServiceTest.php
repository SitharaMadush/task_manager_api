<?php

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

beforeEach(function () {
    // prevent Log::error() from triggering facade
    Mockery::mock('alias:App\Helpers\ErrorLogger')
        ->shouldReceive('log')
        ->andReturnNull();
});

it('can list tasks with filters and user ID', function () {
    $mockRepo = Mockery::mock(TaskRepository::class);

    $mockTasks = EloquentCollection::make([
        new Task(['title' => 'Test 1', 'user_id' => 1]),
        new Task(['title' => 'Test 2', 'user_id' => 1]),
    ]);

    $filters = ['status' => 'completed'];
    $userId = 1;

    $mockRepo->shouldReceive('getUserTasks')
             ->once()
             ->with($filters, $userId)
             ->andReturn($mockTasks);

    $service = new TaskService($mockRepo);

    $result = $service->list($userId, $filters);

    expect($result)->toHaveCount(2);
    expect($result->first())->toBeInstanceOf(Task::class);
    expect($result->first()->user_id)->toBe(1);
});
