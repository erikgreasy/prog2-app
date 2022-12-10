<?php

namespace Tests\Feature;

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

    public function test_store_endpoint_can_be_accessed_only_for_auth()
    {
        $response = $this->postJson(route('assignments.store'));
        $response->assertStatus(401);

        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson(route('assignments.store'));
        $this->assertNotEquals(401, $response->getStatusCode());
    }

    public function test_published_assignments_can_be_fetched_by_everyone()
    {
        $response = $this->getJson(route('assignments.index'));
        $response->assertStatus(200);
    }

    public function test_published_assignment_detail_can_be_fetched_by_everyone()
    {
        $assignment = Assignment::factory()->create();

        $response = $this->getJson(route('assignments.showBySlug', $assignment->slug));
        $response->assertStatus(200);
    }
}
