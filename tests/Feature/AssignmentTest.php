<?php

namespace Tests\Feature;

use App\Enums\AssignmentStatus;
use App\Enums\Role;
use App\Http\Resources\AssignmentResource;
use Tests\TestCase;
use App\Models\User;
use App\Models\Assignment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AssignmentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_store_endpoint_cannot_be_accessed_by_non_logged_users()
    {
        $response = $this->postJson(route('assignments.store'));

        $response->assertStatus(401);
    }

    public function test_store_endpoint_cannot_be_accessed_by_students()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));

        $response = $this->postJson(route('assignments.store'));

        $this->assertNotEquals(401, $response->getStatusCode());
    }

    public function test_store_endpoint_can_be_accessed_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));

        $response = $this->postJson(route('assignments.store'));

        $this->assertNotEquals(401, $response->getStatusCode());
    }

    public function test_store_endpoint_can_be_accessed_by_admin()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));

        $response = $this->postJson(route('assignments.store'));

        $this->assertNotEquals(401, $response->getStatusCode());
    }

    public function test_assignment_can_be_created()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));

        $assignmentData = [
            'title' => 'Assignment title',
            'slug' => 'assignment-title',
            'deadline' => now()->nextWeekday()->toDateTimeLocalString(),
            'excerpt' => 'Lorem ipsum dolor sit',
            'points' => 10,
            'tester_path' => '/path/to/tester',
            'vcs_branch' => '01',
            'is_current' => true,
        ];

        $this->postJson(route('assignments.store'), $assignmentData)->assertCreated();

        $this->assertDatabaseHas('assignments', $assignmentData);
    }

    public function test_new_assignment_cannot_have_existing_slug()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));

        Assignment::factory()->create(['slug' => 'some-slug']);

        $this->postJson(route('assignments.store'), [
            'title' => 'Assignment title',
            'slug' => 'some-slug',
            'deadline' => now()->nextWeekday()->toDateTimeLocalString(),
            'excerpt' => 'Lorem ipsum dolor sit'
        ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('assignments', [
            'title' => 'Assignment title',
            'slug' => 'some-slug',
            'deadline' => now()->nextWeekday()->toDateTimeLocalString(),
            'excerpt' => 'Lorem ipsum dolor sit'
        ]);
    }

    public function test_all_assignments_endpoint_cannot_be_accessed_by_non_auth()
    {
        $this->getJson('/api/assignments')
            ->assertStatus(401);
    }

    public function test_all_assignments_endpoint_cannot_be_assigned_by_students()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));

        $this->getJson('/api/assignments')
            ->assertStatus(403);
    }

    public function test_published_assignments_can_be_fetched_by_everyone()
    {
        $response = $this->getJson(route('assignments.published'));

        $response->assertStatus(200);
    }

    public function test_published_assignment_detail_can_be_fetched_by_slug_by_everyone()
    {
        $assignment = Assignment::factory()->create([
            'published_at' => now()->subDay()
        ]);

        $response = $this->getJson(route('assignments.showBySlug', $assignment->slug));
        $response->assertStatus(200);
    }

    public function test_draft_assignment_detail_cannot_be_fetched_by_slug_by_non_auth ()
    {
        $assignment = Assignment::factory()->create([
            'published_at' => null
        ]);

        $this->getJson("/api/assignments/slug/{$assignment->slug}")
            ->assertNotFound();
    }

    public function test_draft_assignment_detail_cannot_be_fetched_by_slug_by_student ()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::STUDENT->value]));

        $assignment = Assignment::factory()->create([
            'published_at' => null
        ]);

        $this->getJson("/api/assignments/slug/{$assignment->slug}")
            ->assertNotFound();
    }

    public function test_draft_assignment_detail_can_be_fetched_by_slug_by_teacher()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::TEACHER->value]));

        $assignment = Assignment::factory()->create([
            'published_at' => null
        ]);

        $this->getJson("/api/assignments/slug/{$assignment->slug}")
            ->assertSuccessful()
            ->assertJson(AssignmentResource::make($assignment)->resolve());
    }

    public function test_draft_assignment_detail_can_be_fetched_by_slug_by_admin ()
    {
        Sanctum::actingAs(User::factory()->create(['role' => Role::ADMIN->value]));

        $assignment = Assignment::factory()->create([
            'published_at' => null
        ]);

        $this->getJson("/api/assignments/slug/{$assignment->slug}")
            ->assertSuccessful()
            ->assertJson(AssignmentResource::make($assignment)->resolve());
    }
}
