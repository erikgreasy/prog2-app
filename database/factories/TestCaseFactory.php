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
            'cmd_in' => 'cmd in 3 4',
            'std_in' => 'std in 2 3',
            'std_out' => 'std out 4 5',
            'err_out' => 'err out 6 7',
        ];
    }
}
