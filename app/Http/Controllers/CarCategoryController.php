<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarCategory;
use App\Exports\CarCategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Mpdf\Mpdf;

class CarCategoryController extends Controller
{
    public function exportExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch car categories specific to the current company
        $carCategories = CarCategory::where('company_id', $companyId)->get();

        return Excel::download(new CarCategoriesExport($carCategories), 'car_categories_list.xlsx');
    }

    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch car categories specific to the current company
        $CarCategorys = CarCategory::where('company_id', $companyId)->get();
        $html = view('car_category.pdf', compact('CarCategorys'))->render();

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

        return $mpdf->Output('car_category_list.pdf', 'D');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Remove any commas and decimal points from the search term to handle formatted numbers
        $searchUnformatted = str_replace([',', '.00'], '', $search);

        // Query the CarCategory with search, pagination, and company filter
        $CarCategorys = CarCategory::where('company_id', $companyId)
            ->when($search, function ($query) use ($search, $searchUnformatted) {
                return $query->where('car_category_name', 'like', '%' . $search . '%')
                             ->orWhere('price', 'like', '%' . $searchUnformatted . '%'); // Use unformatted search term for price
            })
            ->paginate($perPage);

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
            'price' => 'required|numeric',
        ]);

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        CarCategory::create(array_merge($request->all(), ['company_id' => $companyId]));

        return redirect()->route('car_category.index')->with('success', 'Car Category created successfully.');
    }

    public function edit(CarCategory $CarCategory)
    {
        // Ensure the car category belongs to the current company
        $this->authorizeCompany($CarCategory);

        return view('car_category.edit', compact('CarCategory'));
    }

    public function update(Request $request, CarCategory $CarCategory)
    {
        $request->validate([
            'car_category_name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        // Ensure the car category belongs to the current company
        $this->authorizeCompany($CarCategory);

        $CarCategory->update($request->all());

        return redirect()->route('car_category.index')->with('success', 'CarCategory updated successfully.');
    }

    public function destroy(CarCategory $CarCategory)
    {
        // Ensure the car category belongs to the current company
        $this->authorizeCompany($CarCategory);

        $CarCategory->delete();
        return redirect()->route('car_category.index')->with('success', 'CarCategory deleted successfully.');
    }

    private function authorizeCompany(CarCategory $CarCategory)
    {
        $companyId = app('currentCompanyId');
        if ($CarCategory->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}