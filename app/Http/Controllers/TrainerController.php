<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainingCar;
use App\Models\CarCategory; // Import the CarCategory model

class TrainerController extends Controller
{
    public function getDetails(Request $request, $trainerName)
    {
        \Log::info('Trainer Name:', ['trainer_name' => $trainerName]);

        // Fetch the trainer's details by their name
        $trainer = Trainer::where('trainer_name', $trainerName)->first();

        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }

        // Return the trainer's details as a JSON response
        return response()->json([
            'category' => $trainer->category,
            'plate_no' => $trainer->plate_no,
            'car_name' => $trainer->car_name,
        ]);
    }

    // Display a listing of the training cars
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $trainers = Trainer::when($search, function ($query) use ($search) {
            return $query->where('trainer_name', 'like', '%' . $search . '%')
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

    
    public function store(Request $request)
{
    // Log the incoming request data
    \Log::info('Incoming request data:', $request->all());

    // Validate based on the training type
    $request->validate([
        'trainer_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|unique:trainers,email',
        'experience' => 'required|integer',
        'training_type' => 'required|string|in:Theoretical,Practical,Both',
        // Only validate these fields if the training type is not 'Theoretical'
        'plate_no' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
        'category' => $request->training_type !== 'Theoretical' ? 'required|exists:car_categories,id' : 'nullable',
        'car_name' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
    ]);

    // Fetch the car category name based on the provided category ID if applicable
    $carCategory = $request->training_type !== 'Theoretical'
        ? \DB::table('car_categories')->where('id', $request->category)->first()
        : null;

    if ($request->training_type !== 'Theoretical' && !$carCategory) {
        return redirect()->back()->withErrors(['category' => 'Invalid car category selected.']);
    }

    // Create a new Trainer
    $trainer = Trainer::create([
        'trainer_name' => $request->trainer_name,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'experience' => $request->experience,
        'plate_no' => $request->training_type !== 'Theoretical' ? $request->plate_no : null,
        'car_name' => $request->training_type !== 'Theoretical' ? $request->car_name : null,
        'category' => $request->training_type !== 'Theoretical' ? $carCategory->car_category_name : null,
        'training_type' => $request->training_type, // Save the training type
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

    public function update(Request $request, Trainer $trainer)
{
    // Validate based on the training type
    $request->validate([
        'trainer_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|unique:trainers,email,' . $trainer->id,
        'experience' => 'required|integer',
        'training_type' => 'required|string|in:Theoretical,Practical,Both',
        // Only validate these fields if the training type is not 'Theoretical'
        'plate_no' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
        'category' => $request->training_type !== 'Theoretical' ? 'required|exists:car_categories,id' : 'nullable',
        'car_name' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
    ]);

    // Fetch the car category name if applicable
    $carCategory = $request->training_type !== 'Theoretical'
        ? \DB::table('car_categories')->where('id', $request->category)->first()
        : null;

    // Update the Trainer
    $trainer->update([
        'trainer_name' => $request->trainer_name,
        'phone_number' => $request->phone_number,
        'email' => $request->email,
        'experience' => $request->experience,
        'plate_no' => $request->training_type !== 'Theoretical' ? $request->plate_no : null,
        'car_name' => $request->training_type !== 'Theoretical' ? $request->car_name : null,
        'category' => $request->training_type !== 'Theoretical' ? $carCategory->car_category_name : null,
        'training_type' => $request->training_type, // Update the training type
    ]);

    return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully!');
}

    public function getCarsByCategory($categoryId)
{
    $cars = TrainingCar::where('category', $categoryId)->get(['id', 'name']); // Fetch cars by category
    return response()->json($cars); // Return JSON response
}

public function destroy(Trainer $trainer)
{
    // Delete the trainer from the database
    $trainer->delete();

    // Redirect back to the trainers list with a success message
    return redirect()->route('trainers.index')
        ->with('success', 'Trainer deleted successfully.');
}

}