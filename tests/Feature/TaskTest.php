<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $token = auth('api')->login($this->user); // get JWT
    $this->withHeader('Authorization', "Bearer $token");
});

it('can create a task', function () {
    $response = $this->postJson('/api/tasks', [
        'title' => 'New Task',
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('tasks', [
        'title' => 'New Task',
        'user_id' => $this->user->id,
    ]);
});

it('can list tasks', function () {
    Task::factory()->count(2)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/tasks');

    $response->assertOk();
    $response->assertJsonCount(2);
});

it('can update a task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'Updated Task'
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task'
    ]);
});

it('can delete a task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertOk();
    $this->assertSoftDeleted('tasks', [
        'id' => $task->id
    ]);
});

it('can mark a task as complete', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id, 'completed' => false]);

    $response = $this->patchJson("/api/tasks/{$task->id}/complete");

    $response->assertOk();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true
    ]);
});
