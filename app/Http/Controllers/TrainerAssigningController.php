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
use Illuminate\Support\Facades\Http;

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
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainer assignments specific to the current company
        $trainers_assigning = TrainerAssigning::where('company_id', $companyId)->get();

        return Excel::download(new TrainersAssigningExport($trainers_assigning), 'trainers_assigning_list.xlsx');
    }

    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainer assignments specific to the current company
        $trainers_assigning = TrainerAssigning::where('company_id', $companyId)->get();
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

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Eager load the category relationship and filter by company
        $trainers_assigning = TrainerAssigning::with('category')
            ->where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('trainee_name', 'like', '%' . $search . '%')
                             ->orWhere('trainer_name', 'like', '%' . $search . '%')
                             ->orWhere('start_date', 'like', '%' . $search . '%')
                             ->orWhere('end_date', 'like', '%' . $search . '%')
                             ->orWhere('category_id', 'like', '%' . $search . '%')
                             ->orWhere('plate_no', 'like', '%' . $search . '%')
                             ->orWhere('car_name', 'like', '%' . $search . '%')
                             ->orWhere('total_time', 'like', '%' . $search . '%');
            })->paginate($perPage);

        return view('trainer_assigning.index', compact('trainers_assigning'));
    }

    public function create()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch only active trainers specific to the current company
        $activeTrainers = Trainer::where('status', 'active')->where('company_id', $companyId)->get();

        // Fetch all trainees specific to the current company
        $trainees = Trainee::where('company_id', $companyId)->get();

        // Calculate trainerCounts excluding assignments with passed end dates
        $trainerCounts = TrainerAssigning::where('end_date', '>=', now())
            ->where('company_id', $companyId)
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

    public function getExamResult($traineeId)
    {
        // Mock response directly without calling external API
        $mockExamResult = 80; // Set your simulated score here

        return $mockExamResult; // Use this result directly in your app
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'trainee_name' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|string|exists:trainers,category',
            'plate_no' => 'required|numeric',
            'car_name' => 'required|string|max:255',
            'total_time' => 'required|numeric',
        ]);

        // Get company_id from the logged-in user's profile
        $company_id = auth()->user()->company_id;

        // Fetch the trainee's exam result
        $trainee = Trainee::where('full_name', $request->trainee_name)->first();
        if (!$trainee) {
            return redirect()->back()->with('error', 'Trainee not found.');
        }

        $examResult = $this->getExamResult($trainee->id);

        // Check if the exam result is greater than 74
        if ($examResult === null || $examResult <= 74) {
            return redirect()->back()->with('error', 'Trainee cannot be assigned as their exam result is less than 75.');
        }

        // Create the TrainerAssigning record
        $trainer_assigning = TrainerAssigning::create([
            'trainee_name' => $request->trainee_name,
            'trainer_name' => $request->trainer_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'category_id' => $request->category_id,
            'plate_no' => $request->plate_no,
            'car_name' => $request->car_name,
            'total_time' => $request->input('total_time'),
            'company_id' => $company_id, // Set the company ID
        ]);

        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assigned successfully!');
    }

    public function edit($id)
    {
        $trainer_assigning = TrainerAssigning::find($id);

        if (!$trainer_assigning) {
            return redirect()->route('trainer_assigning.index')->with('error', 'Trainer assignment not found.');
        }

        // Ensure the trainer assignment belongs to the current company
        $this->authorizeCompany($trainer_assigning);

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
            'total_time' => 'required|numeric',
        ]);

        // Ensure the trainer assignment belongs to the current company
        $this->authorizeCompany($trainer_assigning);

        // Update the trainer assigning record
        $trainer_assigning->update([
            'trainee_name' => $request->trainee_name,
            'trainer_name' => $request->trainer_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'category_id' => $request->category_id,
            'plate_no' => $request->plate_no,
            'car_name' => $request->car_name,
            'total_time' => $request->input('total_time'),
        ]);

        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer assignment updated successfully!');
    }

    public function destroy(TrainerAssigning $trainer_assigning)
    {
        // Ensure the trainer assignment belongs to the current company
        $this->authorizeCompany($trainer_assigning);

        $trainer_assigning->delete();
        return redirect()->route('trainer_assigning.index')->with('success', 'Trainer deleted successfully!');
    }

    private function authorizeCompany(TrainerAssigning $trainer_assigning)
    {
        $companyId = app('currentCompanyId');
        if ($trainer_assigning->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}