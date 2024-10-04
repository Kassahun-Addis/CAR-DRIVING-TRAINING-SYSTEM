<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainingCar;

class TrainerController extends Controller
{
    // Display a listing of the trainers
    public function index()
    {
        $trainers = Trainer::all(); // Fetch all trainers
        return view('trainer.index', compact('trainers')); // Return to index view
    }

    // Show the form for creating a new trainer
    public function create()
    {
        $trainingCars = TrainingCar::all(); // Fetch all training cars
        return view('trainer.create', compact('trainingCars')); // Return create view
    }

    // Store a newly created trainer in the database
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

    // Show the form for editing the specified trainer
    public function edit(Trainer $trainer)
    {
        $trainingCars = TrainingCar::all(); // Fetch all training cars
        return view('trainer.edit', compact('trainer', 'trainingCars')); // Return edit view
    }

    // Update the specified trainer in the database
    public function update(Request $request, Trainer $trainer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'experience' => 'required|integer',
            'specialization' => 'required|string|max:255',
            'car_id' => 'required|exists:training_cars,id', // Validate car ID
        ]);

        $trainer->update($request->all()); // Update the trainer record

        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully!');
    }

    // Remove the specified trainer from the database
    public function destroy(Trainer $trainer)
    {
        $trainer->delete(); // Delete the trainer record
        return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully!');
    }
}