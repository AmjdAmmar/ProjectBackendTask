<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\User;

class UserSeeder extends Seeder
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
        //     \App\Models\User::factory($batchCount)->create()->each(function ($User) {
        //         $User->update(['created_from' => $User->created_at->diffForHumans()]);
        //     });

        //     // Output progress
        //     $progress = $i + $batchCount;
        //     $this->command->info("Created {$progress}/{$totalRecords} records");

        //     // Optional: Sleep for a short duration to release memory (adjust as needed)
        //     usleep(50000); // 50 milliseconds
        // }
        \App\Models\User::factory(20)->create();

        // Manually add two records
         \App\Models\User::create(['name' => 'Amjd Ammar', 'email' => 'amjd@example.com', 'password' => bcrypt('12345678') ,'role' =>'owner'/* other fields */]);
         \App\Models\User::create(['name' => 'Ali Doe', 'email' => 'ali@example.com', 'password' => bcrypt('12345678922') ,'role' =>'super-admin'/* other fields */]);
        \App\Models\User::create(['name' => 'Mohammed Doe', 'email' => 'Mohammed@example.com', 'password' => bcrypt('123456789344') ,'role' =>'admin'/* other fields */]);

    }
}
