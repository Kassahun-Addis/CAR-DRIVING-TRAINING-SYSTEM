<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Facades\Auth;


class TraineeController extends Controller
{
    public function create()
    {
        return view('Trainee.addTrainee'); // The path to your form view
    }

    public function store(Request $request)
    {
        // Log the incoming request data
        \Log::info('Storing Trainee data:', $request->all());

        // Validate the incoming request data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'city' => 'required|string',
            'sub_city' => 'required|string',
            'woreda' => 'required|string',
            'house_no' => 'required|numeric',
            'phone_no' => 'required|numeric',
            'po_box' => 'required|numeric',
            'birth_place' => 'required|string',
            'dob' => 'required|date',
            'driving_license_no' => 'nullable|string',
            'license_type' => 'required|string',
            'education_level' => 'nullable|string',
            'disease' => 'nullable|string',
            'blood_type' => 'required|string',
            'receipt_no' => 'nullable|string',
        ]);

        // Create a new Trainee entry
        $trainee = Trainee::create([
            'full_name' => $request->input('full_name'),
            'gender' => $request->input('gender'),
            'nationality' => $request->input('nationality'),
            'city' => $request->input('city'),
            'sub_city' => $request->input('sub_city'),
            'woreda' => $request->input('woreda'),
            'house_no' => $request->input('house_no'),
            'phone_no' => $request->input('phone_no'),
            'po_box' => $request->input('po_box'),
            'birth_place' => $request->input('birth_place'),
            'dob' => $request->input('dob'),
            'existing_driving_lic_no' => $request->input('driving_license_no'),
            'license_type' => $request->input('license_type'),
            'education_level' => $request->input('education_level'),
            'any_case' => $request->input('disease'),
            'blood_type' => $request->input('blood_type'),
            'receipt_no' => $request->input('receipt_no'),
        ]);

        // Log the newly created Trainee
        \Log::info('New Trainee created:', $trainee->toArray());

        // Redirect or return response
        return redirect()->route('trainee.index')->with('success', 'Trainee addedsuccessfully.');
    }

    public function index()
    {
        // Fetch all trainees from the database
        $trainees = Trainee::all();

        // Pass the data to the index view
        return view('Trainee.index', compact('trainees'));
    }
    

    public function edit($id)
    {
        // Find the trainee by id
        $trainee = Trainee::findOrFail($id);

        // Return the edit view with the trainee data
        return view('Trainee.editTrainee', compact('trainee'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'city' => 'required|string',
            'sub_city' => 'required|string',
            'woreda' => 'required|string',
            'house_no' => 'required|numeric',
            'phone_no' => 'required|numeric',
            'po_box' => 'required|numeric',
            'birth_place' => 'required|string',
            'dob' => 'required|date',
            'driving_license_no' => 'nullable|string',
            'license_type' => 'required|string',
            'education_level' => 'nullable|string',
            'disease' => 'nullable|string',
            'blood_type' => 'required|string',
            'receipt_no' => 'nullable|string',
        ]);

        // Find the trainee by id and update the information
        $trainee = Trainee::findOrFail($id);
        $trainee->update($request->all());

        // Redirect to the index page with a success message
        return redirect()->route('trainee.index')->with('success', 'Trainee updated successfully.');
    }

    public function destroy($id)
    {
        // Find the trainee by id and delete it
        $trainee = Trainee::findOrFail($id);
        $trainee->delete();

        // Redirect to the index page with a success message
        return redirect()->route('trainee.index')->with('success', 'Trainee deleted successfully.');
    }

    public function showDashboard()
{
    $user = Auth::user();

    if ($user) {
        $trainee = Trainee::find($user->id); // Assuming the ID matches
        return view('home', compact('trainee')); // Ensure 'home' is the correct view name
    }

    return redirect()->route('login')->withErrors('You are not logged in.');
}
}