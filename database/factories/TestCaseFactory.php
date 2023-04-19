<?php

namespace Database\Factories;

use App\Models\TestScenario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestCase>
 */
class TestCaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cmdin' => 'cmd in 3 4',
            'stdin' => 'std in 2 3',
            'stdout' => 'std out 4 5',
            'errout' => 'err out 6 7',
        ];
    }
}
