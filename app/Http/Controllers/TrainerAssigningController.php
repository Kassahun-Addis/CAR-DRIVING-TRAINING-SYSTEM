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
use App\Exports\TrainersAssigningExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;  



class TrainerAssigningController extends Controller
{

        public function getTraineesByCategory($category)
    {
        \Log::info('Fetching trainees for category:', ['category' => $category]);

        // Use the correct column name 'category' for filtering
        $trainees = Trainee::where('category', $category)->get(['full_name']);

        \Log::info('Fetched trainees:', $trainees->toArray());

        return response()->json($trainees);
    }

    public function exportExcel()
    {
        return Excel::download(new TrainersAssigningExport, 'trainers_assigning_list.xlsx');
    }

   public function exportPdf()
    {
        $trainers_assigning = TrainerAssigning::all();
        $html = view('trainer_assigning.pdf', compact('trainers_assigning'))->render();
    
        // Initialize Mpdf and configure custom font settings
        $mpdf = new Mpdf([
            'format' => 'A4-L', // Landscape orientation
            'default_font' => 'Nyala',
            'fontDir' => [base_path('public/fonts')], // Specify custom font directory
            'fontdata' => [
                'nyala' => [
                    'R' => 'nyala.ttf', // Regular Nyala font
                ],
            ],
            'default_font_size' => 10, // Set the default font size
        ]);
    
        $mpdf->WriteHTML($html);
    
        return $mpdf->Output('trainers_assigning_list.pdf', 'D');
    }

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
    // Fetch only active trainers
    $activeTrainers = Trainer::where('status', 'active')->get();

    // Fetch all trainees
    $trainees = Trainee::all(); // Assuming you have a Trainee model

    // Calculate trainerCounts excluding assignments with passed end dates
    $trainerCounts = TrainerAssigning::where('end_date', '>=', now()) // Only include current or future assignments
        ->select('trainer_name', DB::raw('count(*) as count'))
        ->groupBy('trainer_name')
        ->pluck('count', 'trainer_name')
        ->toArray();

    // Sort active trainers by the count of trainees in ascending order
    $sortedTrainers = $activeTrainers->sortBy(function($trainer) use ($trainerCounts) {
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
        'total_time' => 'required|numeric', // Add validation for total time
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
        'total_time' => $request->input('total_time'), // Store total time

    ]);

    return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assigned successfully!');
}

public function edit($id)
{
    $trainer_assigning = TrainerAssigning::find($id);

    if (!$trainer_assigning) {
        return redirect()->route('trainer_assigning.index')->with('error', 'Trainer assignment not found.');
    }

    // Debugging: Log the trainer_assigning data
    \Log::info('Editing Trainer Assigning:', $trainer_assigning->toArray());

    $activeTrainers = Trainer::where('status', 'active')->get();
    $trainees = Trainee::all();

    $trainerCounts = TrainerAssigning::where('end_date', '>=', now())
        ->select('trainer_name', DB::raw('count(*) as count'))
        ->groupBy('trainer_name')
        ->pluck('count', 'trainer_name')
        ->toArray();

    $sortedTrainers = $activeTrainers->sortBy(function($trainer) use ($trainerCounts) {
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
        'category_id' => 'required|string|exists:trainers,category',
        'plate_no' => 'required|string|max:255',
        'car_name' => 'required|string|max:255',
        'total_time' => 'required|numeric', // Add validation for total time

    ]);

    // Update the trainer assigning record
    $trainer_assigning->update([
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'category_id' => $request->category_id,
        'plate_no' => $request->plate_no,
        'car_name' => $request->car_name,
        'total_time' => $request->input('total_time'), // Update total time
    ]);

    return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assignment updated successfully!');
}

    // Remove the specified trainer from the database
    public function destroy(TrainerAssigning $trainer_assigning)
    {
        $trainer_assigning->delete(); // Delete the trainer_assigning record
        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer deleted successfully!');
    }


}
