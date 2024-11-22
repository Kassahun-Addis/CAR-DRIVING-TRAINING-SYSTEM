<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class SuperAdminCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        // Fetch companies with optional search functionality
        $companies = Company::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('tin', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('company_id', 'like', "%{$search}%"); // Add search by company_id
        })->paginate(10); // Adjust the number as needed

        return view('superadmin.index', compact('companies', 'search'));
    }

    public function create()
    {
        return view('superadmin.create');
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
        ]);

        Company::create($request->all());

        return redirect()->route('superadmin.index')->with('success', 'Company created successfully.');
    }
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('superadmin.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tin' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,jfif,svg|max:2048',
        ]);

        $company = Company::findOrFail($id);

        $logoName = $company->logo;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = $request->file('logo');
            $logoName = $logo->getClientOriginalName();
            $path = $logo->storeAs('company_logos', $logoName, 'public');
        }

        $company->update([
            'name' => $request->input('name'),
            'tin' => $request->input('tin'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'logo' => $logoName,
        ]);

        return redirect()->route('superadmin.index')->with('success', 'Company information updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('superadmin.index')->with('success', 'Company deleted successfully.');
    }
}