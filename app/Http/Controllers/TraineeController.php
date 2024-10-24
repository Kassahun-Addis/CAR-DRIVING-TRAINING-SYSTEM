<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\CarCategory;
use Illuminate\Support\Facades\Auth;
use App\Exports\TraineeExport;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Response;


class TraineeController extends Controller
{
    public function toggleStatus(Request $request, Trainee $trainee)
{
    try {
        // Validate the incoming status
        $validatedData = $request->validate([
            'status' => 'required|string|in:active,inactive',
        ]);

        // Update the trainer's status
        $trainee->status = $validatedData['status'];
        $trainee->save();

        // Return a JSON response
        return response()->json(['status' => 'success', 'newStatus' => $trainee->status]);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error updating trainer status: ' . $e->getMessage());

        // Return a JSON error response
        return response()->json(['status' => 'error', 'message' => 'Failed to update status'], 500);
    }
}

    public function create()
    {
        $carCategories = CarCategory::all(); // Fetch all car categories

        return view('Trainee.addTrainee', compact('carCategories')); // The path to your form view
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
            'full_name_2' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jfif,jpg,gif|max:4096', // Validate image file
            'gender' => 'required|string',
            'gender_1' => 'required|string',
            'nationality' => 'required|string',
            'nationality_1' => 'required|string',
            'city' => 'required|string',
            'city_1' => 'required|string',
            'sub_city' => 'required|string',
            'sub_city_1' => 'required|string',
            'woreda' => 'required|string',
            'woreda_1' => 'required|string',
            'house_no' => 'required|numeric',
            'phone_no' => 'required|numeric',
            'po_box' => 'required|numeric',
            'birth_place' => 'required|string',
            'birth_place_1' => 'required|string',
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
            
            // Store the photo in the storage/app/public/trainee_photos directory
            $path = $photo->storeAs('trainee_photos', $photoName, 'public');

            // Log the file path
            \Log::info('Photo uploaded to:', ['path' => $path]);
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
            'ሙሉ_ስም' => $request->input('full_name_2'),
            'photo' => $photoName,  // Save the photo file name or path
            'gender' => $request->input('gender'),
            'ጾታ' => $request->input('gender_1'),
            'nationality' => $request->input('nationality'),
            'ዜግነት' => $request->input('nationality_1'),
            'city' => $request->input('city'),
            'ከተማ' => $request->input('city_1'),
            'sub_city' => $request->input('sub_city'),
            'ክፍለ_ከተማ' => $request->input('sub_city_1'),
            'woreda' => $request->input('woreda'),
            'ወረዳ' => $request->input('woreda_1'),
            'house_no' => $request->input('house_no'),
            'phone_no' => $request->input('phone_no'),
            'po_box' => $request->input('po_box'),
            'birth_place' => $request->input('birth_place'),
            'የትዉልድ_ቦታ' => $request->input('birth_place_1'),
            'dob' => $request->input('dob'),
            'existing_driving_lic_no' => $request->input('driving_license_no'),
            'license_type' => $request->input('license_type'),
            'education_level' => $request->input('education_level'),
            'blood_type' => $request->input('blood_type'),
            'receipt_no' => $request->input('receipt_no'),
        ]);
    
        // Log the newly created Trainee
        \Log::info('New Trainee created:', $trainee->toArray());
    
        // Redirect or return response
        return redirect()->route('trainee.index')->with('success', 'Trainee added successfully.');
    }


    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $trainees = Trainee::when($search, function ($query) use ($search) {
            return $query->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('gender', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('Trainee.index', compact('trainees'));
   }
    

    public function edit($id)
    {
        $carCategories = CarCategory::all(); // Fetch all car categories

        // Find the trainee by id
        $trainee = Trainee::findOrFail($id);

        // Return the edit view with the trainee data
        return view('Trainee.editTrainee', compact('trainee', 'carCategories'));
    }

    public function update(Request $request, $id)
{
    \Log::info('Incoming request data:', $request->all());

    // Validate the incoming request data
    $request->validate([
        'yellow_card' => 'required|unique:trainees,yellow_card,' . $id, // Ensure unique check ignores current record
        'full_name' => 'required|string|max:255',
        'full_name_2' => 'required|string|max:255',
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
        'photo' => 'nullable|image|mimes:jpeg,png,jfif,jpg,gif|max:4096', // Validate image file
    ]);

    // Find the trainee by id
    $trainee = Trainee::findOrFail($id);

    // Check if a new photo is uploaded
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');

        if ($photo->isValid()) {
            // Use the phone number as the photo name
            $photoName = $request->input('phone_no') . '.' . $photo->getClientOriginalExtension(); 
            
            // Store the photo in the storage/app/public/trainee_photos directory
            $photo->storeAs('trainee_photos', $photoName, 'public');

            // Save the new photo name in the database
            $trainee->photo = $photoName;
        }
    }

    // Update other trainee fields
    $trainee->yellow_card = $request->input('yellow_card');
    $trainee->full_name = $request->input('full_name');
    $trainee->ሙሉ_ስም = $request->input('full_name_2');
    $trainee->gender = $request->input('gender');
    $trainee->ጾታ = $request->input('gender_1');
    $trainee->nationality = $request->input('nationality');
    $trainee->ዜግነት = $request->input('nationality_1');
    $trainee->city = $request->input('city');
    $trainee->ከተማ = $request->input('city_1');
    $trainee->sub_city = $request->input('sub_city');
    $trainee->ክፍለ_ከተማ = $request->input('sub_city_1');
    $trainee->woreda = $request->input('woreda');
    $trainee->ወረዳ = $request->input('woreda_1');
    $trainee->house_no = $request->input('house_no');
    $trainee->phone_no = $request->input('phone_no');
    $trainee->po_box = $request->input('po_box');
    $trainee->birth_place = $request->input('birth_place');
    $trainee->የትዉልድ_ቦታ = $request->input('birth_place_1');
    $trainee->dob = $request->input('dob');
    $trainee->existing_driving_lic_no = $request->input('driving_license_no');
    $trainee->license_type = $request->input('license_type');
    $trainee->education_level = $request->input('education_level');
    $trainee->blood_type = $request->input('blood_type');
    $trainee->receipt_no = $request->input('receipt_no');

    // Save the trainee record
    $trainee->save();

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

// Add this method to your controller
public function exportToExcel()
{
    return Excel::download(new BankCategoryExport, 'trainees.xlsx');
}

// public function showPhoto($photoName)
// {
//     $path = base_path('uploads/trainee_photos/' . $photoName);

//     if (!File::exists($path)) {
//         abort(404, 'Image not found.');
//     }

//     $file = File::get($path);
//     $mimeType = File::mimeType($path);

//     return response($file)->header("Content-Type", $mimeType);
// }


public function showAgreement($id)
{
    //dd($id); // Check if this outputs the correct ID
    $trainee = Trainee::findOrFail($id);
    return view('Trainee.agreement', compact('trainee'));
}

public function downloadAgreement($id)
{
    $trainee = Trainee::findOrFail($id); // Fetch the trainee object

    // Render the view with the trainee data
    $agreementContent = view('Trainee.agreement', ['trainee' => $trainee])->render();

    // Create a PDF from the content
    $pdf = app('dompdf.wrapper');
    $pdf->loadHTML($agreementContent);

    // Download the PDF
    return $pdf->download("agreement_{$id}.pdf");
}

}