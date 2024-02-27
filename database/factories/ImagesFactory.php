<?php

namespace Database\Factories;

use App\Models\Images;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageUrl = $this->faker->imageUrl();

        $imagePath = 'public/images/';
        $imageName = basename($imageUrl);
        $imageContent = file_get_contents($imageUrl);
        Storage::put($imagePath . $imageName, $imageContent);

        $imageableId = $this->faker->numberBetween(1, 100);

        $imageableType = $this->faker->randomElement(['App\Models\Category', 'App\Models\Product', 'App\Models\User']);

        return [
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
            'image1' => $this->faker->imageUrl(),
            'image2' => $imageableType === 'App\Models\Product' ? $this->faker->imageUrl() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
    }
}