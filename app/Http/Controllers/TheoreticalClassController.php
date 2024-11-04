<?php

namespace App\Http\Controllers;

use App\Models\TheoreticalClass;
use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\TrainerAssigning;
use Illuminate\Support\Facades\DB;
use App\Models\Trainer;
use App\Models\Classes;
use App\Exports\TheoreticalClassesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Mpdf\Mpdf;


class TheoreticalClassController extends Controller
{

    public function exportPdf()
    {
        $theoretical_classes = TheoreticalClass::all();
        $html = view('theoretical_classes.pdf', compact('theoretical_classes'))->render();
    
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
    
        return $mpdf->Output('class_assigning_list.pdf', 'D');
    }

    public function exportExcel()
    {
        return Excel::download(new TheoreticalClassesExport, 'theoretical_classes_list.xlsx');
    } 

    public function index(Request $request)
{
    $search = $request->input('search'); // Get the search term
    $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

    // Query the theoretical classes with search and pagination
    $theoretical_classes = TheoreticalClass::when($search, function ($query) use ($search) {
        return $query->where('trainee_name', 'like', '%' . $search . '%')
                     ->orWhere('trainer_name', 'like', '%' . $search . '%')
                     ->orWhere('class_name', 'like', '%' . $search . '%')
                     ->orWhere('start_date', 'like', '%' . $search . '%')
                     ->orWhere('end_date', 'like', '%' . $search . '%');
    })->paginate($perPage);

    return view('theoretical_classes.index', compact('theoretical_classes'));
}

    public function create()
{
    // Fetch all classes
    $classes = Classes::all();

    // Fetch all active trainees
    $assignedTrainees = TheoreticalClass::pluck('trainee_name')->toArray();
    $trainees = Trainee::where('status', 'active')
                       ->whereNotIn('full_name', $assignedTrainees)
                       ->get();


    $trainers = Trainer::where('status', 'active')
                        ->get();
    // Get the current date
    $currentDate = now();

    // Calculate the number of trainees per class where the end_date is greater than or equal to the current date
    $classCounts = TheoreticalClass::where('end_date', '>=', $currentDate)
        ->select('class_name', DB::raw('count(*) as count'))
        ->groupBy('class_name')
        ->pluck('count', 'class_name')
        ->toArray();

    // Sort classes by the count of trainees in ascending order
    $sortedClasses = $classes->sortBy(function($class) use ($classCounts) {
        return $classCounts[$class->class_name] ?? 0;
    });

    return view('theoretical_classes.create', compact('sortedClasses', 'classCounts', 'trainees', 'trainers'));
}

    public function store(Request $request)
    {
        // Validate and store a new class
        $request->validate([
            'trainee_name' => 'required|string|max:255',
            'trainer_name' => 'required|arr|max:255',
            'class_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        TheoreticalClass::create($request->all());

        return redirect()->route('theoretical_class.index')
                         ->with('success', 'Theoretical class created successfully.');
    }

    public function show(TheoreticalClass $theoreticalClass)
    {
        // Show a single class details
        return view('theoretical_classes.show', compact('theoreticalClass'));
    }

    public function edit($id)
{
    // Fetch the theoretical class being edited 
    $theoreticalClass = TheoreticalClass::findOrFail($id);
    
    // Fetch all classes
    $classes = Classes::all();

    // Get the current date
    $currentDate = now();

    // Manually count trainees per class for classes where end_date >= current date
    $traineeCounts = DB::table('theoretical_classes')
        ->where('end_date', '>=', $currentDate) // Only consider classes that haven't ended
        ->select('class_name', DB::raw('COUNT(*) as count'))
        ->groupBy('class_name')
        ->pluck('count', 'class_name');

    // Sort classes by the count of trainees in ascending order
    $sortedClasses = $classes->sortBy(function($class) use ($traineeCounts) {
        return $traineeCounts[$class->class_name] ?? 0;  // Default to 0 if no trainees
    });

    // Fetch all trainees
    $trainees = Trainee::all();

    // Fetch all trainees
    $trainers = Trainer::all();

    return view('theoretical_classes.edit', compact('theoreticalClass','trainers', 'trainees', 'sortedClasses', 'traineeCounts'));
}

    public function update(Request $request, TheoreticalClass $theoreticalClass)
    {
        // Validate and update class
        $request->validate([
            'trainee_name' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $theoreticalClass->update($request->all());

        return redirect()->route('theoretical_class.index')
                         ->with('success', 'Theoretical class updated successfully.');
    }

    public function destroy(TheoreticalClass $theoreticalClass)
    {
        \Log::info('Deleting theoretical class:', ['id' => $theoreticalClass->id]);

        // Delete a class
        $theoreticalClass->delete();
        return redirect()->route('theoretical_class.index')
                         ->with('success', 'Theoretical class deleted successfully.');
    }
}