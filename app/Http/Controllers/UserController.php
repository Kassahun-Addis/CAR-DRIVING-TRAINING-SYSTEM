<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default to 10 if not specified
        $search = $request->input('search'); // Get the search query

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Query the users, applying the search filter and company filter if provided
        $users = User::where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('role', 'like', "%{$search}%");
            })->paginate($perPage);

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'company_id' => 'required|exists:companies,company_id', // Validate company_id
        ]);

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $companyId, // Set company_id
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Ensure the user belongs to the current company
        $this->authorizeCompany($user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure the user belongs to the current company
        $this->authorizeCompany($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
            'company_id' => 'required|exists:companies,company_id', // Validate company_id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->company_id = $request->company_id; // Set company_id
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Ensure the user belongs to the current company
        $this->authorizeCompany($user);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    private function authorizeCompany(User $user)
    {
        $companyId = app('currentCompanyId');
        if ($user->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}