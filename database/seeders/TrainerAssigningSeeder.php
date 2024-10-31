<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainerAssigning;
use App\Models\Trainer;
use App\Models\Trainee;

class TrainerAssigningSeeder extends Seeder
{
    public function run()
    {
        // Fetch all trainers and trainees
        $trainers = Trainer::all();
        $trainees = Trainee::all();

        // Check if there are any trainers available
        if ($trainers->isEmpty()) {
            $this->command->info('No trainers found. Please seed trainers first.');
            return;
        }

        foreach ($trainees as $trainee) {
            TrainerAssigning::create([
                'trainee_name' => $trainee->full_name,
                'trainer_name' => $trainers->random()->full_name, // Random trainer
                'start_date' => now(),
                'end_date' => now()->addWeeks(4),
                'category_id' => 1, // Example category ID
                'plate_no' => '12345',
                'car_name' => 'Sample Car',
                'total_time' => 10,
            ]);
        }
    }
}