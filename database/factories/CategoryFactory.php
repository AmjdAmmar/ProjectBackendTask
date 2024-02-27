<?php

namespace Database\Factories;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->boolean ? 1 : 0,
            'created_at' => $createdAt,
            
            'updated_at' => now(),
        ];
    }
}
