<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;
use App\Models\TrainerAssigning;
use Illuminate\Validation\Rule;
use App\Models\TrainingCar;
use App\Models\Trainer;
use App\Models\Trainee;
use Illuminate\Support\Facades\DB;



class TrainerAssigningController extends Controller
{
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

    // \Log::info('Trainers Assigning:', $trainers_assigning->toArray());

    return view('trainer_assigning.index', compact('trainers_assigning'));
}

public function create()
{
    // Fetch all trainers
    $trainers = Trainer::all();

    // Fetch all trainees
    $trainees = Trainee::all(); // Assuming you have a Trainee model

    // Calculate trainerCounts excluding assignments with passed end dates
    $trainerCounts = TrainerAssigning::where('end_date', '>=', now()) // Only include current or future assignments
        ->select('trainer_name', DB::raw('count(*) as count'))
        ->groupBy('trainer_name')
        ->pluck('count', 'trainer_name')
        ->toArray();

    // Sort trainers by the count of trainees in ascending order
    $sortedTrainers = $trainers->sortBy(function($trainer) use ($trainerCounts) {
        return $trainerCounts[$trainer->trainer_name] ?? 0;
    });

    return view('trainer_assigning.create', compact('sortedTrainers', 'trainerCounts', 'trainees'));
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

    // Create the TrainerAssigning record
    $trainer_assigning = TrainerAssigning::create([
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'category_id' => $request->category_id, // Use the found category id
        'plate_no' => $request->plate_no,
        'car_name' => $request->car_name,
    ]);

    return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assigned successfully!');
}

public function edit($id)
{
    $trainer_assigning = TrainerAssigning::find($id);

    // Fetch all trainers
    $trainers = Trainer::all();

     // Fetch all trainees
     $trainees = Trainee::all();

    // Calculate trainerCounts excluding assignments with passed end dates
    $trainerCounts = TrainerAssigning::where('end_date', '>=', now()) // Only include current or future assignments
        ->select('trainer_name', DB::raw('count(*) as count'))
        ->groupBy('trainer_name')
        ->pluck('count', 'trainer_name')
        ->toArray();

    // Sort trainers by the count of trainees in ascending order
    $sortedTrainers = $trainers->sortBy(function($trainer) use ($trainerCounts) {
        return $trainerCounts[$trainer->trainer_name] ?? 0;
    });

    return view('trainer_assigning.edit', compact('trainer_assigning', 'trainees', 'sortedTrainers', 'trainerCounts'));
}

    // Update the specified trainer in the database

    public function update(Request $request, TrainerAssigning $trainer_assigning)
{
    $request->validate([
        'trainee_name' => 'required|string|max:255',
        'trainer_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'category_id' => 'required|exists:trainers,category',
        'plate_no' => 'required|string|max:255',
        'car_name' => 'required|string|max:255',
    ]);

    $trainer_assigning->update($request->all());

    session()->flash('success', 'Trainer assignment updated successfully!');
    return redirect()->route('trainer_assigning.index');
}

    // Remove the specified trainer from the database
    public function destroy(TrainerAssigning $trainer_assigning)
    {
        $trainer_assigning->delete(); // Delete the trainer_assigning record
        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer deleted successfully!');
    }


//     public function getTrainersWithCount()
// {
//     \Log::info('Fetching trainer with count group by trainees: ');

//     try {
//         // Fetch the count of trainees for each trainer
//         $trainerCounts = TrainerAssigning::select('trainer_name', \DB::raw('COUNT(*) as count'))
//             ->groupBy('trainer_name')
//             ->get();

//         // Prepare an array to hold the final results
//         $finalTrainer = [];

//         foreach ($trainerCounts as $trainer) {
//             $finalTrainer[] = [
//                 'trainer_name' => $trainer->trainer_name,
//                 'count' => $trainer->count,
//                 'display' => "{$trainer->trainer_name} ({$trainer->count} Trainee)"
//             ];
//         }

//         \Log::info('Fetched trainers: ', $finalTrainer);

//         if (empty($finalTrainer)) {
//             \Log::info('No trainees found for trainer: ');
//             return response()->json([]); // Return an empty array
//         }

//         $sortedTrainer = collect($finalTrainer)->sortBy('count');

//         return response()->json($sortedTrainer->values()->all()); // Use values() to reset keys
//     } catch (\Exception $e) {
//         \Log::error('Error fetching trainers: ' . $e->getMessage());
//         return response()->json(['error' => 'Unable to fetch trainers: ' . $e->getMessage()], 500);
//     }
// }

}
