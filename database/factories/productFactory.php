<?php

namespace Database\Factories;
use Carbon\Carbon;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class productFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
        
        return [
            'category_id' => \App\Models\Category::factory(), // This will create a category using its factory
            'name' => $this->faker->word,
            'user_id' => \App\Models\user::factory(), 
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->boolean ? 1 : 0,
            'created_at' => $createdAt,
            'updated_at' => now(),
        ];
    }
}
