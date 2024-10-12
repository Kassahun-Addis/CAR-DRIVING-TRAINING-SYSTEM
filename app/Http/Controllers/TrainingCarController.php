<?php

namespace App\Http\Controllers;

use App\Models\TrainingCar;
use Illuminate\Http\Request;
use App\Models\CarCategory;

class TrainingCarController extends Controller
{
    // Display a listing of the training cars
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $trainingCars = TrainingCar::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('training_cars.index', compact('trainingCars'));
   }

    // Show the form for creating a new training car
    public function create()
    {
        // Fetch all car categories
        $categories = CarCategory::all();

        // Pass the categories to the view
        return view('training_cars.create', compact('categories'));
    }

    // Store a newly created training car in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'plate_no' => 'required|string|max:20|unique:training_cars,plate_no',
        ]);

        TrainingCar::create($request->all());

        return redirect()->route('training_cars.index')->with('success', 'Training Car created successfully!');
    }

    // Show the form for editing the specified training car
    // public function edit(TrainingCar $trainingCar)
    // {
    //     return view('training_cars.edit', compact('trainingCar'));
    // }

    // // Update the specified training car in the database
    // public function update(Request $request, TrainingCar $trainingCar)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'category' => 'required|string|max:255',
    //         'model' => 'nullable|string|max:255',
    //         'year' => 'nullable|integer|min:1900|max:' . date('Y'),
    //         'plate_no' => 'required|string|max:20|unique:training_cars,plate_no,' . $trainingCar->id,
    //     ]);

    //     $trainingCar->update($request->all());

    //     return redirect()->route('training_cars.index')->with('success', 'Training Car updated successfully!');
    // }

    public function edit($id)
    {
        // Fetch the training car by ID
        $trainingCar = TrainingCar::findOrFail($id);

        // Fetch all car categories
        $categories = CarCategory::all();

        // Pass the training car and categories to the view
        return view('training_cars.edit', compact('trainingCar', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'model' => 'nullable',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'plate_no' => 'required|unique:training_cars,plate_no,' . $id,
        ]);

        // Find the training car and update its data
        $trainingCar = TrainingCar::findOrFail($id);
        $trainingCar->update($request->all());

        return redirect()->route('training_cars.index')->with('success', 'Training car updated successfully.');
    }

    // Remove the specified training car from the database
    public function destroy(TrainingCar $trainingCar)
    {
        $trainingCar->delete();
        return redirect()->route('training_cars.index')->with('success', 'Training Car deleted successfully!');
    }
}