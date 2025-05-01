<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create 2 demo users
         $users = [
            [
                'name' => 'Alice Tester',
                'email' => 'alice@example.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Bob Developer',
                'email' => 'bob@example.com',
                'password' => Hash::make('password')
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Create 5 tasks per user
            for ($i = 1; $i <= 5; $i++) {
                Task::create([
                    'title' => "Task #{$i} for {$user->name}",
                    'user_id' => $user->id,
                    'completed' => $i % 2 === 0 // mark even tasks as completed
                ]);
            }
        }
    }
}
