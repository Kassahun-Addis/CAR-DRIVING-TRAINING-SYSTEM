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
        return Excel::download(new CarCategoriesExport, 'car_categories_list.xlsx');
   }

   public function exportPdf()
    {
        $CarCategorys = CarCategory::all();
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
            'price' => 'required|numeric',

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
            'price' => 'required|numeric',

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
