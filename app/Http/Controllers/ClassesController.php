<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
        public function index()
    {
        // Retrieve paginated classes, e.g., 10 per page
        $classes = Classes::paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        // Show the form to create a new class
        return view('classes.create');
    }

    public function store(Request $request)
    {
        // Validate and save a new class
        $request->validate([
            'class_name' => 'required|string|max:255',

        ]);

        Classes::create($request->all());

        return redirect()->route('classes.index')
                         ->with('success', 'Class created successfully.');
    }

    public function show(Classes $class)
    {
        // Show details of a single class
        return view('classes.show', compact('class'));
    }

    public function edit(Classes $class)
    {
        // Show the form to edit an existing class
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, Classes $class)
    {
        // Validate and update class details
        $request->validate([
            'class_name' => 'required|string|max:255',
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
                         ->with('success', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        // Delete a class
        $class->delete();
        return redirect()->route('classes.index')
                         ->with('success', 'Class deleted successfully.');
    }
}