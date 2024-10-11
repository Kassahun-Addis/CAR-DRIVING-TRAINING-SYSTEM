<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
   
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $banks = Bank::when($search, function ($query) use ($search) {
            return $query->where('bank_name', 'like', '%' . $search . '%');
        })->paginate($perPage);
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

        ]);

        Bank::create($request->all());
        return redirect()->route('banks.index')->with('success', 'Bank created successfully.');
    }

    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $bank->update($request->all());
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
}
