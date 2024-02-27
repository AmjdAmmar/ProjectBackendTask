<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\product;
class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $batchSize = 1000; // Adjust the batch size based on your needs
        // $totalRecords = 500000;

        // for ($i = 0; $i < $totalRecords; $i += $batchSize) {
        //     $batchCount = min($batchSize, $totalRecords - $i);

        //     // Create records for the current batch
        //     \App\Models\product::factory($batchCount)->create()->each(function ($product) {
        //         $product->update(['created_from' => $product->created_at->diffForHumans()]);
        //     });

        //     // Output progress
        //     $progress = $i + $batchCount;
        //     $this->command->info("Created {$progress}/{$totalRecords} records");

        //     // Optional: Sleep for a short duration to release memory (adjust as needed)
        //     usleep(50000); // 50 milliseconds
        // }
        \App\Models\product::factory(20)->create();

    }
}
