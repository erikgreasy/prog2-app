<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\TestCase;
use App\Models\TestScenario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DemoSeeder extends Seeder
{
    public function run()
    {
        Submission::query()->delete();
        User::query()->delete();
        Assignment::query()->delete();

        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('whateverpassword'),
            'role' => Role::ADMIN,
        ]);

        User::factory(30)->create([
            'role' => Role::STUDENT
        ]);

        Assignment::factory(5)
            ->has(TestScenario::factory(5)->has(TestCase::factory(5), 'cases'))
            ->create();
    }
}
