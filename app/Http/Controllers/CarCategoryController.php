<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;


class CarCategoryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the CarCategory with search and pagination
         $CarCategorys = CarCategory::when($search, function ($query) use ($search) {
            return $query->where('car_category_name', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('car_category.index', compact('CarCategorys'));
   }

    public function create()
    {
        return view('car_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_category_name' => 'required|string|max:255',

        ]);

        CarCategory::create($request->all());
        return redirect()->route('car_category.index')->with('success', 'Car Category created successfully.');
    }

    public function edit(CarCategory $CarCategory)
    {
        return view('car_category.edit', compact('CarCategory'));
    }

    public function update(Request $request, CarCategory $CarCategory)
    {
        $request->validate([
            'car_category_name' => 'required|string|max:255',
        ]);

        $CarCategory->update($request->all());
        return redirect()->route('car_category.index')->with('success', 'CarCategory updated successfully.');
    }

    public function destroy(CarCategory $CarCategory)
    {
        $CarCategory->delete();
        return redirect()->route('car_category.index')->with('success', 'CarCategory deleted successfully.');
    }
}
