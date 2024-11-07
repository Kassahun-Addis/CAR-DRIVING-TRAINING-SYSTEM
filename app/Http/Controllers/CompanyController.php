<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tin' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'company_id' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,jfif,svg|max:2048',
        ], [
            'logo.required' => 'The logo field is required.',
        ]);

        $logoName = null;

        // Check if a logo was uploaded
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = $request->file('logo');
            
            // Store the logo with a unique name, or use original name if desired
            $logoName = $logo->getClientOriginalName(); // Optionally use $logoName = uniqid() . '.' . $logo->extension();
            
            // Save logo in the 'company_logos' folder within the public storage
            $path = $logo->storeAs('company_logos', $logoName, 'public');
            \Log::info('Logo uploaded to:', ['path' => $path]);
        } else {
            \Log::info('No logo uploaded, using null for logo field.');
        }

        // Create the company record, including the logo path if it was uploaded
        Company::create([
            'name' => $request->input('name'),
            'tin' => $request->input('tin'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'company_id' => $request->input('company_id'),
            'logo' => $logoName, // Store the logo name in the database
        ]);

        return redirect()->route('companies.create')->with('success', 'Company information recorded successfully.');
    }

    public function index()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch only the current company
        $companies = Company::where('company_id', $companyId)->get();
        return view('companies.index', compact('companies'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);

        // Ensure the company belongs to the current company context
        $this->authorizeCompany($company);

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tin' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,jfif,svg|max:2048',
            'company_id' => 'required|string|max:255',
        ],[
            'logo.required' => 'The logo field is required.',
        ]);

        $logoName = null;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = $request->file('logo');
            $logoName = $logo->getClientOriginalName();
            $path = $logo->storeAs('company_logos', $logoName, 'public');
        }


        $company = Company::findOrFail($id);

        // Ensure the company belongs to the current company context
        $this->authorizeCompany($company);

        $company->update([
            'name' => $request->input('name'),
            'tin' => $request->input('tin'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'logo' => $logoName,
        ]);
        
        return redirect()->route('companies.index')->with('success', 'Company information updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        // Ensure the company belongs to the current company context
        $this->authorizeCompany($company);

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    private function authorizeCompany(Company $company)
    {
        $companyId = app('currentCompanyId');
        if ($company->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}