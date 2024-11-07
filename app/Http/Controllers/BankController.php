<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Exports\BanksExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Mpdf\Mpdf;

class BankController extends Controller
{
    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch banks specific to the current company
        $banks = Bank::where('company_id', $companyId)->get();
        $html = view('banks.pdf', compact('banks'))->render();

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

        return $mpdf->Output('bank_list.pdf', 'D');
    }

    public function exportExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch banks specific to the current company
        $banks = Bank::where('company_id', $companyId)->get();

        return Excel::download(new BanksExport($banks), 'banks_list.xlsx');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Query the banks with search, pagination, and company filter
        $banks = Bank::where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('bank_name', 'like', '%' . $search . '%')
                             ->orWhere('id', 'like', '%' . $search . '%'); // Add more attributes as needed
            })
            ->paginate($perPage);

        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            // No need to validate company_id if it's set automatically
        ]);

        // Example: Get company_id from the logged-in user's profile
        $company_id = auth()->user()->company_id;

        Bank::create([
            'bank_name' => $request->bank_name,
            'company_id' => $company_id,
        ]);

        return redirect()->route('banks.index')->with('success', 'Bank created successfully.');
    }

    public function edit(Bank $bank)
    {
        // Ensure the bank belongs to the current company
        $this->authorizeCompany($bank);

        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            // No need to validate company_id if it's set automatically
        ]);

        // Ensure the bank belongs to the current company
        $this->authorizeCompany($bank);

        // Example: Get company_id from the logged-in user's profile
        $company_id = auth()->user()->company_id;

        $bank->update([
            'bank_name' => $request->bank_name,
            'company_id' => $company_id,
        ]);

        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        // Ensure the bank belongs to the current company
        $this->authorizeCompany($bank);

        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }

    private function authorizeCompany(Bank $bank)
    {
        $companyId = app('currentCompanyId');
        if ($bank->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}