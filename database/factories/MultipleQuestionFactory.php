<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MultipleQuestion>
 */
class MultipleQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->paragraph(3),
            'response_one' => fake()->paragraph(1),
            'response_two' => fake()->paragraph(1),
            'response_tree' => fake()->paragraph(1),
            'response_four' => fake()->paragraph(1),
            'gabarito' => fake()->numberBetween(1,4),
            'punctuation' => 1,
            'course_id' => 4,
            'discipline_id' => 7,
        ];
    }
}
