<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;

class UserController extends Controller
{

    public function toggleVerification(User $user)
    {
        $user->active = !$user->active; // Toggle the active status
        $user->save();
    
        return response()->json(['success' => true, 'active' => $user->active]);
    }
    
    public function showUnverifiedUsers()
    {
        //$users = User::where('active', false)->paginate(10); // Paginate the results
        //$users = User::paginate(10); // Retrieve all users with pagination
        $users = User::orderBy('active', 'asc')->paginate(10); // Order by 'active' status
        return view('admin.verify_users', compact('users'));
    }

    // private function notifySuperAdmin($user)
    // {
    //     $superAdminEmail = 'superadmin@example.com'; // Replace with actual super admin email
    //     \Log::info('Sending email to super admin for user: ' . $user->email);
    //     Mail::to($superAdminEmail)->send(new UserRegistered($user));
    // }

    public function verifyUser(User $user)
    {
        if (!$user->active) {
            $user->active = true;
            $user->save();

            return redirect()->route('admin.unverifiedUsers')->with('success', 'User verified successfully.');
        }

        return redirect()->route('admin.unverifiedUsers')->with('info', 'User is already verified.');
    }

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
            'company_id' => 'required|exists:companies,company_id',
        ]);

        $companyId = app('currentCompanyId');

        // Capture the created user instance
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $companyId,
            'active' => false, // Set user as inactive by default
        ]);

        // Send email to super admin
       // $this->notifySuperAdmin($user);

        // Send verification email to user
       // Mail::to($user->email)->send(new UserVerification($user));

        return redirect()->route('users.index')->with('success', 'User registered successfully. Awaiting verification.');
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