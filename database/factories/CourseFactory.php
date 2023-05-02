<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'slug' => 'titulo-do-curso',
            'duration' => '1 ano',
            'description' => fake()->paragraph(3),
            'institution' => 'setbal',
            'polo_id' => fake()->numberBetween(1,3),
            'status' => 'inactive'
        ];
    }
}
