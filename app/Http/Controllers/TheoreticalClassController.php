<?php

namespace App\Http\Controllers;

use App\Models\TheoreticalClass;
use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\TrainerAssigning;
use Illuminate\Support\Facades\DB;
use App\Models\Trainer;
use App\Models\Classes;

class TheoreticalClassController extends Controller
{
    public function index()
    {
        // Fetch all classes
        //$theoretical_classes = TheoreticalClass::all();
        $theoretical_classes = TheoreticalClass::paginate(10); // Adjust the number as needed

        return view('theoretical_classes.index', compact('theoretical_classes'));
    }

    public function create()
{
    // Fetch all classes
    $classes = Classes::all();

    // Fetch all trainees
    $trainees = Trainee::all(); // Assuming you have a Trainee model

    // Calculate the number of trainees per class
    $classCounts = TheoreticalClass::select('class_name', DB::raw('count(*) as count'))
        ->groupBy('class_name')
        ->pluck('count', 'class_name')
        ->toArray();

    // Sort classes by the count of trainees in ascending order
    $sortedClasses = $classes->sortBy(function($class) use ($classCounts) {
        return $classCounts[$class->class_name] ?? 0;
    });

    return view('theoretical_classes.create', compact('sortedClasses', 'classCounts', 'trainees'));
}

    public function store(Request $request)
    {
        // Validate and store a new class
        $request->validate([
            'trainee_name' => 'required|string|max:255',
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

    
    public function edit(TheoreticalClass $theoreticalClass)
{
    // Fetch all classes
    $classes = Classes::all();

    // Fetch all trainees
    $trainees = Trainee::all(); // Ensure this is included

    // Calculate the number of trainees per class
    $traineeCounts = TheoreticalClass::select('class_name', DB::raw('count(*) as count'))
        ->groupBy('class_name')
        ->pluck('count', 'class_name')
        ->toArray();

    // Sort trainees by the count of trainees in ascending order
    $sortedClasses = $trainees->sortBy(function($trainee) use ($traineeCounts) {
        return $traineeCounts[$trainee->class_name] ?? 0;
    });

    return view('theoretical_classes.edit', compact('theoreticalClass', 'sortedClasses', 'traineeCounts', 'trainees')); // Pass the trainees variable here
}

    public function update(Request $request, TheoreticalClass $theoreticalClass)
    {
        // Validate and update class
        $request->validate([
            'trainee_name' => 'required|string|max:255',
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