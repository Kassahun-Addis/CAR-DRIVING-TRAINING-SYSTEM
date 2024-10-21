<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;
use App\Models\TrainerAssigning;
use Illuminate\Validation\Rule;
use App\Models\TrainingCar;
use App\Models\Trainer;


class TrainerAssigningController extends Controller
{

    // Method to fetch trainer details based on the trainer's ID
    // public function getTrainerDetails($id)
    // {
    //     // Find the trainer by ID
    //     $trainer = Trainer::with('category')->find($id);

    //     if ($trainer) {
    //         return response()->json([
    //             'category' => $trainer->category->car_category_name, // Assuming a relationship with category
    //             'plate_no' => $trainer->plate_no,
    //             'car_name' => $trainer->car_name,
    //         ]);
    //     }

    //     return response()->json(['error' => 'Trainer not found'], 404);
    // }

    // Display a listing of the training cars
    public function index(Request $request)
{
    \Log::info('Request data:', $request->all());

    $search = $request->input('search');
    $perPage = $request->input('perPage', 10);

    // Eager load the category relationship
    $trainers_assigning = TrainerAssigning::with('category')
        ->when($search, function ($query) use ($search) {
            return $query->where('trainee_name', 'like', '%' . $search . '%')
                         ->orWhere('trainer_name', 'like', '%' . $search . '%');
        })->paginate($perPage);

    \Log::info('Trainers Assigning:', $trainers_assigning->toArray());

    return view('trainer_assigning.index', compact('trainers_assigning'));
}

    // Show the form for creating a new trainer 
    public function create()
    {
        $trainers = Trainer::all();
        //$trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        $plates = []; // Initialize plates as an empty array
        return view('trainer_assigning.create', compact('carCategories', 'trainers')); // Return create view with car categories
        //return view('trainer_assigning.create');
    }

    // Store a newly created trainer in the database
    public function store(Request $request)
{
    \Log::info('Incoming request data:', $request->all());

    // Validate the request
    $request->validate([
        'trainee_name' => 'required|string|max:255',
        'trainer_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'category_id' => 'required|string|exists:trainers,category', // Validate against car_category_name
        'plate_no' => 'required|numeric', 
        'car_name' => 'required|string|max:255',
    ]);

    // Find the category id based on the category name
    $categoryId = \DB::table('trainers')->where('category', $request->category_id)->value('id');

    // Check if the category ID was found
    if (!$categoryId) {
        return redirect()->back()->withErrors(['category_id' => 'Selected category ID is invalid.']);
    }

    // Create the TrainerAssigning record
    $trainer_assigning = TrainerAssigning::create([
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'category_id' => $categoryId, // Use the found category id
        'plate_no' => $request->plate_no,
        'car_name' => $request->car_name,
    ]);

    return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assigned successfully!');
}

    // Show the form for editing the specified trainer
    public function edit(TrainerAssigning $trainer_assigning)
{
    $carCategories = CarCategory::all(); // Fetch all car categories

    // Fetch plates based on the current category of the trainer assigning
    $plates = TrainingCar::where('category', $trainer_assigning->category_id)->pluck('plate_no', 'id');

    return view('trainer_assigning.edit', compact('trainer_assigning', 'carCategories', 'plates')); // Pass all necessary variables to the view
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
            'plate_no' => 'required|string|max:255',
            // 'plate_no' => [
            //     'required',
            //     'string',
            //     'max:255',
            //     Rule::unique('trainer_assignings')->ignore($trainer_assigning->assigning_id),
            // ],
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


    public function getPlatesByCategory($categoryId)
    {
        \Log::info('Fetching plates for category ID: ' . $categoryId);
        
        try {
            $plates = TrainingCar::where('category', $categoryId)->pluck('plate_no', 'id');
            
            if ($plates->isEmpty()) {
                \Log::info('No plates found for category ID: ' . $categoryId);
            }
            
            return response()->json($plates);
        } catch (\Exception $e) {
            \Log::error('Error fetching plates: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch plates'], 500);
        }
    }

    public function getPlatesWithCount($categoryId)
{
    \Log::info('Fetching plates with count for category ID: ' . $categoryId);
    
    try {
        // Fetch plate numbers and count occurrences from the trainer_assignings table
        $trainerPlates = TrainerAssigning::select('plate_no', \DB::raw('COUNT(*) as count'))
            ->where('category_id', $categoryId)
            ->groupBy('plate_no');

        // Fetch plate numbers from the training_cars table
        $carPlates = TrainingCar::select('plate_no')
            ->where('category', $categoryId) // Ensure this references the correct field
            ->get();

        // Convert the trainer plates to a collection for easier manipulation
        $trainerPlatesCollection = $trainerPlates->get()->keyBy('plate_no');

        // Prepare an array to hold the final results
        $finalPlates = [];

        // Iterate over the car plates and set counts
        foreach ($carPlates as $carPlate) {
            if (isset($trainerPlatesCollection[$carPlate->plate_no])) {
                // If the plate exists in trainer_assignings, use its count
                $count = $trainerPlatesCollection[$carPlate->plate_no]->count;
                $finalPlates[] = [
                    'plate_no' => $carPlate->plate_no,
                    'count' => $count,
                    'display' => "{$carPlate->plate_no} ({$count} Trainee)", // Always append "Trainee"
                ];
            } else {
                // If the plate exists only in training_cars, set count to 0
                $finalPlates[] = [
                    'plate_no' => $carPlate->plate_no,
                    'count' => 0,
                    'display' => "{$carPlate->plate_no} (0 Trainee)", // Always append "Trainee"
                ];
            }
        }

        // Add any plates from trainer_assignings that are not in training_cars
        foreach ($trainerPlatesCollection as $plateNo => $trainerPlate) {
            if (!in_array($plateNo, array_column($finalPlates, 'plate_no'))) {
                $count = $trainerPlate->count;
                $finalPlates[] = [
                    'plate_no' => $plateNo,
                    'count' => $count,
                    'display' => "{$plateNo} ({$count} Trainee)", // Always append "Trainee"
                ];
            }
        }

        // Log the fetched plates
        \Log::info('Fetched plates: ', $finalPlates);

        // If no plates are found, return an empty array
        if (empty($finalPlates)) {
            \Log::info('No plates found for category ID: ' . $categoryId);
            return response()->json([]); // Return an empty array
        }

        // Sort the plates by count in ascending order
        $sortedPlates = collect($finalPlates)->sortBy('count');

        // Return the sorted plates with their counts
        return response()->json($sortedPlates->values()->all()); // Use values() to reset keys
    } catch (\Exception $e) {
        \Log::error('Error fetching plates: ' . $e->getMessage());
        return response()->json(['error' => 'Unable to fetch plates: ' . $e->getMessage()], 500);
    }
}

}
