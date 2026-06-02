<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'created_by' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'banner' => null,
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
            'target_items' => fake()->numberBetween(10, 100),
            'status' => fake()->randomElement(['upcoming', 'active', 'completed', 'cancelled']),
        ];
    }
}
