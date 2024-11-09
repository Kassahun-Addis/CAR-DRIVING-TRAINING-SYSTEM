<?php

namespace App\Http\Controllers;

use App\Models\TrainingCar;
use Illuminate\Http\Request;
use App\Models\CarCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingCarsExport;
use Mpdf\Mpdf;

class TrainingCarController extends Controller
{
    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch training cars specific to the current company
        $trainingCars = TrainingCar::where('company_id', $companyId)->get();
        $html = view('training_cars.pdf', compact('trainingCars'))->render();

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

        return $mpdf->Output('vehicles_list.pdf', 'D');
    }

    public function exportExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch training cars specific to the current company
        $trainingCars = TrainingCar::where('company_id', $companyId)->get();

        return Excel::download(new TrainingCarsExport($trainingCars), 'vehicles_list.xlsx');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Query the training cars with search, pagination, and company filter
        $trainingCars = TrainingCar::where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('category', 'like', '%' . $search . '%')
                             ->orWhere('model', 'like', '%' . $search . '%')
                             ->orWhere('year', 'like', '%' . $search . '%')
                             ->orWhere('plate_no', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);

        return view('training_cars.index', compact('trainingCars'));
    }

    public function create()
    {
        // Fetch all car categories
        $categories = CarCategory::all();

        // Pass the categories to the view
        return view('training_cars.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'plate_no' => 'required|string|max:20|unique:training_cars,plate_no',
        ]);

        // Get company_id from the logged-in user's profile
        $company_id = auth()->user()->company_id;

        TrainingCar::create([
            'name' => $request->name,
            'category' => $request->category,
            'model' => $request->model,
            'year' => $request->year,
            'plate_no' => $request->plate_no,
            'company_id' => $company_id, // Set the company ID
        ]);

        return redirect()->route('training_cars.index')->with('success', 'Training Car created successfully!');
    }

    public function edit($id)
    {
        // Fetch the training car by ID
        $trainingCar = TrainingCar::findOrFail($id);

        // Ensure the training car belongs to the current company
        $this->authorizeCompany($trainingCar);

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

        // Ensure the training car belongs to the current company
        $this->authorizeCompany($trainingCar);

        $trainingCar->update($request->all());

        return redirect()->route('training_cars.index')->with('success', 'Training car updated successfully.');
    }

    public function destroy(TrainingCar $trainingCar)
    {
        // Ensure the training car belongs to the current company
        $this->authorizeCompany($trainingCar);

        $trainingCar->delete();
        return redirect()->route('training_cars.index')->with('success', 'Training Car deleted successfully!');
    }

    private function authorizeCompany(TrainingCar $trainingCar)
    {
        $companyId = app('currentCompanyId');
        if ($trainingCar->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}