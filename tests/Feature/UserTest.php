<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_cannot_be_accessed_without_auth()
    {
        $this->getJson('/api/users')
            ->assertStatus(401);
    }

    public function test_users_index_cannot_be_accessed_by_student()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));
        
        $this->getJson('/api/users')
            ->assertStatus(403);
    }

    public function test_users_index_cannot_be_accessed_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));
        
        $this->getJson('/api/users')
            ->assertStatus(403);
    }

    public function test_users_index_can_be_retrieved_by_admin()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        User::factory(9)->create();

        $this->getJson('/api/users')
            ->assertSuccessful()
            ->assertJsonCount(10);
    }

    public function test_user_detail_cannot_be_accessed_without_auth()
    {
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(401);
    }

    public function test_user_detail_cannot_be_accessed_by_student()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(403);
    }

    public function test_user_detail_cannot_be_accessed_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(403);
    }


    public function test_user_detail_can_be_retrieved_by_admin()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertSuccessful()
            ->assertJson($user->toArray());
    }
}
