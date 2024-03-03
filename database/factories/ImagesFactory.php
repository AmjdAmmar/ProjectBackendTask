<?php

namespace Database\Factories;

use App\Models\Images;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // try {
        //     $response = Http::get($this->faker->imageUrl());
        //     $imageContent = $response->body();
        // } catch (ConnectionException $exception) {
        //     // Handle connection exception, log error, or return default image content
        //     $imageContent = file_get_contents('default_image_url.jpg'); // Provide a default image URL or path
        // }

        // $imagePath = 'public/images/';
        // $imageName = uniqid() . '.jpg'; // Generate a unique image name
        // Storage::put($imagePath . $imageName, $imageContent);

        $imageableId = $this->faker->numberBetween(1, 100);

        $imageableType = $this->faker->randomElement(['App\Models\Category', 'App\Models\Product', 'App\Models\User']);

        return [
            'url' => $this->faker->imageUrl(),

            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
            // 'image1' => 'images/' . $imageName, // Store the image path instead of URL
            // 'image2' => $imageableType === 'App\Models\Product' ? $this->faker->imageUrl() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
