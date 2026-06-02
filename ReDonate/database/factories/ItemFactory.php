<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'event_id' => null,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(),
            'condition' => fake()->randomElement(['new', 'like_new', 'good', 'fair']),
            'quantity' => fake()->numberBetween(1, 5),
            'location' => fake()->city(),
            'delivery_method' => fake()->randomElement(['pickup', 'delivery', 'both']),
            'status' => fake()->randomElement(['draft', 'active', 'claimed', 'completed', 'cancelled']),
            'images' => [fake()->imageUrl(), fake()->imageUrl()],
            'views' => fake()->numberBetween(0, 100),
        ];
    }
}
