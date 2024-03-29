<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'points' => 10,
            'slug' => str()->slug($this->faker->words(4, asText: true)),
            'deadline' => Carbon::now()->addMonth(),
            'excerpt' => $this->faker->sentences(3, true),
            'vcs_branch' => '01',
        ];
    }
}
