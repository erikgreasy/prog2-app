<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'assignment_id' => $assignment = Assignment::inRandomOrder()->first(),
            'points' => $this->faker->numberBetween(0, 10),
            'report' => '',
            'ip' => $this->faker->ipv4(),
        ];
    }
}
