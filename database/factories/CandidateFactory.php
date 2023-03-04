<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->userName(),
            'source' => fake()->text(50),
            'owner' => fake()->randomElement([1,2]),
            'created_by' => fake()->randomElement([1,2])
        ];
    }
}
