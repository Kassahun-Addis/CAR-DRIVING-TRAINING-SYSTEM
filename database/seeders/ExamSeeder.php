<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Trainee;

class ExamSeeder extends Seeder
{
    public function run()
    {
        // Assuming you have some trainees in your database
        $trainees = Trainee::all();

        foreach ($trainees as $trainee) {
            Exam::create([
                'trainee_id' => $trainee->id,
                'score' => rand(60, 100), // Random score between 60 and 100
            ]);
        }
    }
}