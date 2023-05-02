<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerService>
 */
class CustomerServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sector' => 'support',
            'subject' => fake()->name(),
            'description' => fake()->paragraph(3),
            'status' => 'open',
            'user_id' => 1
        ];
    }
}
