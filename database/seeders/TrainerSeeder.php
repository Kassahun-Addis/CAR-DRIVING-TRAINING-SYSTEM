<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        Trainer::create([
            'trainer_name' => 'John Doe',
            'status' => 'active',
            // Add other necessary fields
        ]);

        // Add more trainers as needed
    }
}