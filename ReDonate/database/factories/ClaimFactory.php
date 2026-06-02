<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claim>
 */
class ClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'message' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
            'pickup_date' => fake()->optional()->dateTimeBetween('+1 days', '+14 days')->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
