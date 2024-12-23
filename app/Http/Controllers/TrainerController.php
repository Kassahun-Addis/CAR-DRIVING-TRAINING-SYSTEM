<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainingCar;
use App\Models\CarCategory; // Import the CarCategory model
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainersExport;
use Mpdf\Mpdf;




class TrainerController extends Controller
{

    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainers specific to the current company
        $trainers = Trainer::where('company_id', $companyId)->get();
        $html = view('Trainer.pdf', compact('trainers'))->render();

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

        return $mpdf->Output('trainers_list.pdf', 'D');
    }

    public function exportExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainers specific to the current company
        $trainers = Trainer::where('company_id', $companyId)->get();

        return Excel::download(new TrainersExport($trainers), 'trainers_list.xlsx');
    }

       // TrainerController.php
public function toggleStatus(Request $request, Trainer $trainer)
{
    try {
        // Validate the incoming status
        $validatedData = $request->validate([
            'status' => 'required|string|in:active,inactive',
        ]);

        // Update the trainer's status
        $trainer->status = $validatedData['status'];
        $trainer->save();

        // Return a JSON response
        return response()->json(['status' => 'success', 'newStatus' => $trainer->status]);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error updating trainer status: ' . $e->getMessage());

        // Return a JSON error response
        return response()->json(['status' => 'error', 'message' => 'Failed to update status'], 500);
    }
}

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

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Query the trainers with search, pagination, and company filter
        $trainers = Trainer::where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('trainer_name', 'like', '%' . $search . '%')
                             ->orWhere('phone_number', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%')
                             ->orWhere('experience', 'like', '%' . $search . '%')
                             ->orWhere('training_type', 'like', '%' . $search . '%')
                             ->orWhere('plate_no', 'like', '%' . $search . '%')
                             ->orWhere('category', 'like', '%' . $search . '%')
                             ->orWhere('car_name', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);

        return view('trainer.index', compact('trainers'));
    }

    public function create()
    {
        $trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer.create', compact('trainingCars', 'carCategories')); // Return create view with car categories
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:trainers,email',
            'experience' => 'required|integer',
            'training_type' => 'required|string|in:Theoretical,Practical,Both',
            'plate_no' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
            'category' => $request->training_type !== 'Theoretical' ? 'required|exists:car_categories,id' : 'nullable',
            'car_name' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
        ]);

    // Get company_id from the logged-in user's profile
    $company_id = auth()->user()->company_id;

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
        'training_type' => $request->training_type,
        'company_id' => $company_id, // Set the company ID
    ]);

        return redirect()->route('trainers.index')->with('success', 'Trainer registered successfully!');
    }

    // Show the form for editing the specified trainer
    public function edit(Trainer $trainer)
    {
        // Ensure the trainer belongs to the current company
        $this->authorizeCompany($trainer);

        $trainingCars = TrainingCar::all(); // Fetch all training cars
        $carCategories = CarCategory::all(); // Fetch all car categories
        return view('trainer.edit', compact('trainer', 'trainingCars', 'carCategories')); // Return edit view with car categories
    }

    public function update(Request $request, Trainer $trainer)
    {
        $request->validate([
            'trainer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'experience' => 'required|integer',
            'training_type' => 'required|string|in:Theoretical,Practical,Both',
            'plate_no' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
            'category' => $request->training_type !== 'Theoretical' ? 'required|exists:car_categories,id' : 'nullable',
            'car_name' => $request->training_type !== 'Theoretical' ? 'required|string|max:255' : 'nullable',
        ]);

        // Ensure the trainer belongs to the current company
        $this->authorizeCompany($trainer);

        // Get company_id from the logged-in user's profile
        $company_id = auth()->user()->company_id;

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
            'training_type' => $request->training_type,
            'company_id' => $company_id, // Update the company ID
        ]);

        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully!');
    }

public function getCarsByCategory($categoryId)
{
    // Fetch the category name from the car_categories table
    $category = CarCategory::find($categoryId);

    if (!$category) {
        return response()->json(['error' => 'Category not found'], 404);
    }

    \Log::info('Fetching cars for category:', ['categoryId' => $categoryId, 'categoryName' => $category->car_category_name]);

    try {
        // Fetch cars where the category matches the car_category_name
        $cars = TrainingCar::where('category', $category->car_category_name)->get(['id', 'name']);
        \Log::info('Cars fetched:', ['cars' => $cars->toArray()]);
        return response()->json($cars);
    } catch (\Exception $e) {
        \Log::error('Error fetching cars: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function getPlateNumberByCarId($carId)
{
    try {
        $car = TrainingCar::find($carId);
        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }
        return response()->json(['plate_no' => $car->plate_no]);
    } catch (\Exception $e) {
        \Log::error('Error fetching plate number: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function destroy(Trainer $trainer)
{
    // Ensure the trainer belongs to the current company
    $this->authorizeCompany($trainer);

    $trainer->delete();
    return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully.');
}

private function authorizeCompany(Trainer $trainer)
{
    $companyId = app('currentCompanyId');
    if ($trainer->company_id !== $companyId) {
        abort(403, 'Unauthorized action.');
    }
}
}