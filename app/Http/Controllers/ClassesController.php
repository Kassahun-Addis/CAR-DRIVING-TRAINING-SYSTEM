<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use App\Exports\ClassesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Mpdf\Mpdf;

class ClassesController extends Controller
{

    public function exportPdf()
    {
        $classes = Classes::all();
        $html = view('classes.pdf', compact('classes'))->render();
    
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
    
        return $mpdf->Output('classes_list.pdf', 'D');
    }

        public function exportExcel()
    {
        return Excel::download(new ClassesExport, 'classes_list.xlsx');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10
    
        // Query the classes with search and pagination
        $classes = Classes::when($search, function ($query) use ($search) {
            return $query->where('class_name', 'like', '%' . $search . '%')
                         ->orWhere('class_id', 'like', '%' . $search . '%'); // Add more attributes as needed
        })->paginate($perPage);
    
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