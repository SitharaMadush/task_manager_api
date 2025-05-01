<?php

namespace App\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getUserTasks(array $filters = [], int $userId): Collection;
    public function create(array $data): Task;
    public function findById(int $id): Task;
    public function update(int $id, array $data): Task;
    public function delete(int $id): bool;
}