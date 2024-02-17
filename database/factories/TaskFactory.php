<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::all()->random()->id,
            'task_name' => fake()->word(),
            'task_description' => fake()->paragraph,
            'task_priority' => fake()->randomElement(['low' ,'medium', 'high']),
            'task_due' => fake()->dateTimeBetween('-10 years', '+10 years'),
        ];

    }
}
