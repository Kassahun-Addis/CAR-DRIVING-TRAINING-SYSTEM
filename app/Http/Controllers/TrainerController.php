<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainingCar;
use App\Models\CarCategory; // Import the CarCategory model

class TrainerController extends Controller
{
    // Display a listing of the training cars
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $trainers = Trainer::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('trainer.index', compact('trainers'));
   }

    // Show the form for creating a new trainer
    public function create()
    {
        $trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer.create', compact('trainingCars', 'carCategories')); // Return create view with car categories
    }

    // Store a newly created trainer in the database
    public function store(Request $request)
{
    // Log the incoming request data
    \Log::info('Incoming request data:', $request->all());

    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|unique:trainers,email',
        'experience' => 'required|integer',
        'plate_no' => 'required|string|max:255',
        'category' => 'required|exists:car_categories,id', // Validate category ID
        'car_name' => 'required|string|max:255', // Validate car make
    ]);

    // Create a new Trainer
    $trainer = Trainer::create([
        'name' => $request->name,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'experience' => $request->experience,
        'plate_no' => $request->plate_no,
        'car_name' => $request->car_name, // Store the car make
        'category' => $request->category, // Store the selected category
    ]);

    return redirect()->route('trainers.index')->with('success', 'Trainer registered successfully!');
}

    // Show the form for editing the specified trainer
    public function edit(Trainer $trainer)
    {
        $trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer.edit', compact('trainer', 'trainingCars', 'carCategories')); // Return edit view with car categories
    }

    // Update the specified trainer in the database
    public function update(Request $request, Trainer $trainer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'experience' => 'required|integer',
            'plate_no' => 'required|string|max:255',
            'car_id' => 'required|exists:training_cars,id',
            'category' => 'required|exists:car_categories,id', // Validate category ID
        ]);
    
        $trainer->update($request->all());
    
        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully!');
    }

    // Remove the specified trainer from the database
    public function destroy(Trainer $trainer)
    {
        $trainer->delete(); // Delete the trainer record
        return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully!');
    }

    public function getCarsByCategory($categoryId)
{
    $cars = TrainingCar::where('category', $categoryId)->get(['id', 'name']); // Fetch cars by category
    return response()->json($cars); // Return JSON response
}

}