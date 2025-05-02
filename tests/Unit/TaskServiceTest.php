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
    new Task(['title' => 'Go Shopping', 'user_id' => 1]),
    new Task(['title' => 'Watch a movie', 'user_id' => 1]),
    ]);

    $filters = ['status' => 'completed'];
    $userId = 1;
    $mockRepo->shouldReceive('getUserTasks')
        ->once()
        ->with($userId, $filters)
        ->andReturn($mockTasks);

    $service = new TaskService($mockRepo);
    $result = $service->list($userId, $filters);
    expect($result)->toHaveCount(2);
    expect($result->first())->toBeInstanceOf(Task::class);
    expect($result->first()->user_id)->toBe(1);
});

it('it can create a task', function () {

    $mockRepo = Mockery::mock(TaskRepository::class);

    $dataArray = ['title' => 'New Task', 'user_id' => 1];
    $task = new Task($dataArray);

    $mockRepo->shouldReceive('create')
        ->once()
        ->with($dataArray)
        ->andReturn($task);

    $service = new TaskService($mockRepo);
    $result = $service->create($dataArray);

    expect($result)->toBeInstanceOf(Task::class);
    expect($result->title)->toBe('New Task');
});


it('it can update a task', function () {
    $mockRepo = Mockery::mock(TaskRepository::class);
    $existingTask = new Task(['title' => 'Old Title']);
    $existingTask->id = 1;

    $updateData = ['title' => 'Updated Title'];

    $updatedTask = new Task(['title' => 'Updated Title']);
    $updatedTask->id = 1;

    $mockRepo->shouldReceive('findById')
        ->once()
        ->with(1)
        ->andReturn($existingTask);

    $mockRepo->shouldReceive('update')
        ->once()
        ->with(1, $updateData)
        ->andReturn($updatedTask);

    $service = new TaskService($mockRepo);
    $result = $service->update(1, $updateData);

    expect($result)->toBeInstanceOf(Task::class);
    expect($result->title)->toBe('Updated Title');
});



