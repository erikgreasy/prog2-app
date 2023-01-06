<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_index_endpoint_cannot_be_accessed_without_auth()
    {
        $this->getJson('/api/students')
            ->assertStatus(401);
    }

    public function test_students_index_endpoint_cannot_be_accessed_by_student()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));

        $this->getJson('/api/students')
            ->assertStatus(403);
    }

    public function test_students_index_endpoint_can_be_accessed_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));
        User::factory(10)->create(['role' => Role::STUDENT->value]);

        $this->getJson('/api/students')
            ->assertSuccessful()
            ->assertJsonCount(10);
    }

    public function test_students_index_endpoint_can_be_accessed_by_admin()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        User::factory(10)->create(['role' => Role::STUDENT->value]);

        $this->getJson('/api/students')
            ->assertSuccessful()
            ->assertJsonCount(10);
    }

    public function test_students_index_endpoint_include_only_students()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        $students = User::factory(10)->create(['role' => Role::STUDENT->value]);
        $admins = User::factory(10)->create(['role' => Role::ADMIN->value]);
        $teachers = User::factory(10)->create(['role' => Role::TEACHER->value]);

        $this->getJson('/api/students')
            ->assertSuccessful()
            ->assertJsonCount(10)
            ->assertJson($students->toArray())
            ->assertJsonMissing($admins->toArray())
            ->assertJsonMissing($teachers->toArray());
    }

    public function test_student_detail_cannot_be_accessed_without_auth()
    {
        $user = User::factory()->create(['role' => Role::STUDENT->value]);

        $this->getJson("/api/students/{$user->id}")
            ->assertStatus(401);
    }

    public function test_student_detail_cannot_be_accessed_by_student()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));
        $user = User::factory()->create(['role' => Role::STUDENT->value]);

        $this->getJson("/api/students/{$user->id}")
            ->assertStatus(403);
    }

    public function test_student_detail_can_be_retrieved_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));
        $user = User::factory()->create(['role' => Role::STUDENT->value]);

        $this->getJson("/api/students/{$user->id}")
            ->assertSuccessful()
            ->assertJson($user->toArray());
    }

    public function test_student_detail_can_be_retrieved_by_admin()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        $user = User::factory()->create(['role' => Role::STUDENT->value]);

        $this->getJson("/api/students/{$user->id}")
            ->assertSuccessful()
            ->assertJson($user->toArray());
    }

    /**
     * Since Students and other roles all all just User model,
     * we want to check if accessing student detail with user id
     * which belongs to non-student returns 404, if it is student
     * then we want to return correct data.
     */
    public function test_student_detail_retrieves_only_student()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));
        $student = User::factory()->create(['role' => Role::STUDENT->value]);
        $teacher = User::factory()->create(['role' => Role::TEACHER->value]);
        $admin = User::factory()->create(['role' => Role::ADMIN->value]);

        $this->getJson("/api/students/{$teacher->id}")
            ->assertNotFound();
        
        $this->getJson("/api/students/{$admin->id}")
            ->assertNotFound();

        $this->getJson("/api/students/{$student->id}")
            ->assertSuccessful()
            ->assertJson($student->toArray());
    }
}
