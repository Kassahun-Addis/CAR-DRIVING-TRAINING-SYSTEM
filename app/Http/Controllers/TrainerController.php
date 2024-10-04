<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainingCar;


class TrainerController extends Controller
{

    public function create()
{
    $trainingCars = TrainingCar::all(); // Assuming you have a model named TrainingCar
    return view('Trainer.create', compact('trainingCars'));
}
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|unique:trainers,email',
        'experience' => 'required|integer',
        'specialization' => 'required|string|max:255',
        'car_id' => 'required|exists:training_cars,id', // Validate car ID
    ]);

    // Create a new Trainer
    $trainer = Trainer::create([
        'name' => $request->name,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'experience' => $request->experience,
        'specialization' => $request->specialization,
        'car_id' => $request->car_id,
    ]);

    // Optional: Log the creation of the trainer
    \Log::info('New trainer created: ', ['trainer' => $trainer]);

    return redirect()->route('trainers.index')->with('success', 'Trainer registered successfully!');
}
}
