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
    
        // Get the last trainee record to increment the custom ID
        $lastTrainee = Trainee::orderBy('id', 'desc')->first();
        
        // Generate the next custom ID
        if ($lastTrainee) {
            $lastCustomId = (int)$lastTrainee->customid;
            $newCustomId = str_pad($lastCustomId + 1, 3, '0', STR_PAD_LEFT); // Increment and pad to 3 digits
        } else {
            $newCustomId = '001';  // First trainee ID
        }
    
        // Validate the incoming request data
        $request->validate([
            'yellow_card' => 'required|unique:trainees,yellow_card',
            'full_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jfif,jpg,gif|max:4096', // Validate image file
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
        ], [
            'yellow_card.unique' => 'The yellow card number must be unique.',
            'yellow_card.required' => 'The yellow card field is required.',
        ]);

            // Initialize the photo name variable
    $photoName = null;

    // Check if a photo was uploaded
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        
        if ($photo->isValid()) { // Check if the file is valid
            // Use the phone number as the photo name
            $photoName = $request->input('phone_no') . '.' . $photo->getClientOriginalExtension(); 
            
            $uploadPath = base_path('uploads/trainee_photos');
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Create directory if it doesn't exist
            }
            
            // Move the file
            $photo->move($uploadPath, $photoName);
            
            // Log the file path
            \Log::info('Photo uploaded to:', ['path' => $uploadPath . '\\' . $photoName]);
        } else {
            \Log::error('Photo upload failed');
        }
    } else {
        \Log::info('No photo uploaded, using null for photo field.');
    }

    
    
        // Create a new Trainee entry, including the generated custom ID
        $trainee = Trainee::create([
            'customid' => $newCustomId,  // Save the generated custom ID
            'yellow_card' => $request->input('yellow_card'),
            'full_name' => $request->input('full_name'),
            'photo' => $photoName,  // Save the photo file name or path
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
        return redirect()->route('trainee.index')->with('success', 'Trainee added successfully.');
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
            'yellow_card' => $request->input('yellow_card'),
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