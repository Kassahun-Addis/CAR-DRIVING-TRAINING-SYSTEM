<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;
use App\Models\TrainerAssigning;
use Illuminate\Validation\Rule;



class TrainerAssigningController extends Controller
{
    // Display a listing of the training cars
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $trainers_assigning = TrainerAssigning::when($search, function ($query) use ($search) {
            return $query->where('trainee_name', 'like', '%' . $search . '%')
                        ->orWhere('trainer_name', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('trainer_assigning.index', compact('trainers_assigning'));
   }

    // Show the form for creating a new trainer
    public function create()
    {
        //$trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer_assigning.create', compact('carCategories')); // Return create view with car categories
        //return view('trainer_assigning.create');
    }

    // Store a newly created trainer in the database
    public function store(Request $request)
{
    \Log::info('Incoming request data:', $request->all());

    $request->validate([
        'trainee_name' => 'required|string|max:255',
        'trainer_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'category_id' => 'required|exists:car_categories,id', // Ensure the category exists
        'plate_no' => 'required|numeric|unique:trainer_assignings,plate_no',
        'car_name' => 'required|string|max:255',
    ]);

    $trainer_assigning = TrainerAssigning::create([
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'category_id' => $request->category_id, // Ensure this references the correct field
        'plate_no' => $request->plate_no,
        'car_name' => $request->car_name,
    ]);

    return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assigned successfully!');
}

    // Show the form for editing the specified trainer
    public function edit(TrainerAssigning $trainer_assigning)
    {
        //$trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer_assigning.edit', compact('trainer_assigning', 'carCategories')); // Pass trainer_assigning to the view
        // Return edit view with car categories
    }

    // Update the specified trainer in the database

    public function update(Request $request, TrainerAssigning $trainer_assigning)
    {
        $request->validate([
            'trainee_name' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|exists:car_categories,id',
            'plate_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('trainer_assignings')->ignore($trainer_assigning->assigning_id),
            ],
            'car_name' => 'required|string|max:255',
        ]);
    
        $trainer_assigning->update($request->all());
    
        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer updated successfully!');
    }

    // Remove the specified trainer from the database
    public function destroy(TrainerAssigning $trainer_assigning)
    {
        $trainer_assigning->delete(); // Delete the trainer_assigning record
        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer deleted successfully!');
    }

//     public function getCarsByCategory($categoryId)
// {
//     $cars = TrainingCar::where('category', $categoryId)->get(['id', 'name']); // Fetch cars by category
//     return response()->json($cars); // Return JSON response
// }
}
