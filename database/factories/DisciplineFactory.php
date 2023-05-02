<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dicipline>
 */
class DisciplineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution' => 'setbal',
            'title' => fake()->name(),
            'slug' => 'slug-titulo-disciplina',
            'year' => fake()->numberBetween(1,4),
            'semester' => fake()->numberBetween(1,8),
            'workload' => fake()->randomNumber(3), // carga horária
            'period' => fake()->randomNumber(1),
            'credits' => fake()->randomNumber(2),
            'description' => fake()->paragraph(10),
            'quiz' => 'inactive',
            'status' => 'inactive',
            'course_id' => fake()->numberBetween(1,4),
            'user_id' => fake()->numberBetween(2,4),
        ];
    }
}
