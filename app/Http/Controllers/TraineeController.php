<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\CarCategory;
use Illuminate\Support\Facades\Auth;
use App\Exports\TraineeExport;
use Illuminate\Support\Facades\Storage;
//use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Response;
use App\Exports\TraineesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;

class TraineeController extends Controller
{
    public function exportToExcel()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainees specific to the current company
        $trainees = Trainee::where('company_id', $companyId)->get();

        return Excel::download(new TraineesExport($trainees), 'trainees.xlsx');
    }

    public function exportPdf()
    {
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Fetch trainees specific to the current company
        $trainees = Trainee::where('company_id', $companyId)->get();
        $html = view('Trainee.pdf', compact('trainees'))->render();

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

        return $mpdf->Output('trainee_list.pdf', 'D');
    }

    public function toggleStatus(Request $request, Trainee $trainee)
    {
        try {
            // Ensure the trainee belongs to the current company
            $this->authorizeCompany($trainee);

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
        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Get the last trainee record to increment the custom ID
        $lastTrainee = Trainee::where('company_id', $companyId)->orderBy('id', 'desc')->first();

        // Generate the next custom ID
        $newCustomId = $lastTrainee ? str_pad((int)$lastTrainee->customid + 1, 3, '0', STR_PAD_LEFT) : '001';

        // Validate the incoming request data
        $request->validate([
            'yellow_card' => 'required|unique:trainees,yellow_card',
            'full_name' => 'required|string|max:255',
            'full_name_2' => 'required|string|max:255',
            'email' => 'nullable|email|unique:trainees,email',
            'tin_no' => 'nullable|numeric|unique:trainees,tin_no',
            'photo' => 'nullable|image|mimes:jpeg,png,jfif,jpg,gif|max:4096',
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
            'category' => 'required|string',
            'education_level' => 'nullable|string',
            'blood_type' => 'required|string',
            'receipt_no' => 'nullable|string',
        ], [
            'yellow_card.unique' => 'The yellow card number must be unique.',
            'yellow_card.required' => 'The yellow card field is required.',
            'email.unique' => 'The email must be unique.',
            'tin_no.unique' => 'The TIN number must be unique.',
        ]);

        $photoName = null;

        // Check if a photo was uploaded
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photo = $request->file('photo');
            $photoName = $request->input('phone_no') . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('trainee_photos', $photoName, 'public');
            \Log::info('Photo uploaded to:', ['path' => $path]);
        } else {
            \Log::info('No photo uploaded, using null for photo field.');
        }

        try {
            // Create a new Trainee entry
            $trainee = Trainee::create([
                'customid' => $newCustomId,
                'yellow_card' => $request->input('yellow_card'),
                'full_name' => $request->input('full_name'),
                'ሙሉ_ስም' => $request->input('full_name_2'),
                'email' => $request->input('email'),
                'tin_no' => $request->input('tin_no'),
                'photo' => $photoName,
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
                'category' => $request->input('category'),
                'education_level' => $request->input('education_level'),
                'blood_type' => $request->input('blood_type'),
                'receipt_no' => $request->input('receipt_no'),
                'company_id' => $companyId, // Set the company ID
            ]);

            \Log::info('New Trainee created:', $trainee->toArray());

            return redirect()->route('trainee.index')->with('success', 'Trainee added successfully.');
        } catch (\Exception $e) {
            \Log::error('Error saving Trainee:', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'An error occurred while saving the trainee data. Please try again or contact support.');
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Query the trainees with search, pagination, and company filter
        $trainees = Trainee::where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                return $query->where('full_name', 'like', '%' . $search . '%')
                             ->orWhere('yellow_card', 'like', '%' . $search . '%')
                             ->orWhere('gender', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%')
                             ->orWhere('ሙሉ_ስም', 'like', '%' . $search . '%')
                             ->orWhere('tin_no', 'like', '%' . $search . '%')
                             ->orWhere('nationality', 'like', '%' . $search . '%')
                             ->orWhere('city', 'like', '%' . $search . '%')
                             ->orWhere('ከተማ', 'like', '%' . $search . '%')
                             ->orWhere('sub_city', 'like', '%' . $search . '%')
                             ->orWhere('ክፍለ_ከተማ', 'like', '%' . $search . '%')
                             ->orWhere('woreda', 'like', '%' . $search . '%')
                             ->orWhere('ወረዳ', 'like', '%' . $search . '%')
                             ->orWhere('phone_no', 'like', '%' . $search . '%')
                             ->orWhere('po_box', 'like', '%' . $search . '%')
                             ->orWhere('birth_place', 'like', '%' . $search . '%')
                             ->orWhere('የትዉልድ_ቦታ', 'like', '%' . $search . '%')
                             ->orWhere('dob', 'like', '%' . $search . '%')
                             ->orWhere('category', 'like', '%' . $search . '%')
                             ->orWhere('education_level', 'like', '%' . $search . '%')
                             ->orWhere('blood_type', 'like', '%' . $search . '%')
                             ->orWhere('receipt_no', 'like', '%' . $search . '%');
            })->paginate($perPage);

        return view('Trainee.index', compact('trainees'));
    }

    public function edit($id)
    {
        $carCategories = CarCategory::all(); // Fetch all car categories

        // Find the trainee by id
        $trainee = Trainee::findOrFail($id);

        // Ensure the trainee belongs to the current company
        $this->authorizeCompany($trainee);

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
            'email' => 'nullable|email|unique:trainees,email,' . $id, // Ensure unique check ignores current record
            'tin_no' => 'nullable|numeric|unique:trainees,tin_no,' . $id, // Ensure unique check ignores current record
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
            'category' => 'required|string',
            'education_level' => 'nullable|string',
            'disease' => 'nullable|string',
            'blood_type' => 'required|string',
            'receipt_no' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jfif,jpg,gif|max:4096', // Validate image file
        ]);

        // Find the trainee by id
        $trainee = Trainee::findOrFail($id);

        // Ensure the trainee belongs to the current company
        $this->authorizeCompany($trainee);

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
        $trainee->email = $request->input('email');
        $trainee->tin_no = $request->input('tin_no');
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
        $trainee->category = $request->input('category');
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

        // Ensure the trainee belongs to the current company
        $this->authorizeCompany($trainee);

        $trainee->delete();

        // Redirect to the index page with a success message
        return redirect()->route('trainee.index')->with('success', 'Trainee deleted successfully.');
    }

    public function showDashboard()
    {
        $user = Auth::user();

        if ($user) {
            $trainee = Trainee::where('company_id', app('currentCompanyId'))->find($user->id); // Assuming the ID matches
            return view('home', compact('trainee')); // Ensure 'home' is the correct view name
        }

        return redirect()->route('trainee.login')->withErrors('You are not logged in.');
    }

        public function showAgreement($id)
    {
        // Attempt to retrieve the current company ID, if set
        $companyId = app()->bound('currentCompanyId') ? app('currentCompanyId') : null;

        if ($companyId) {
            // If companyId is set, ensure the trainee belongs to the current company
            $trainee = Trainee::where('company_id', $companyId)->findOrFail($id);
        } else {
            // If companyId is not set, allow access as a guest
            // Fetch the trainee without company restriction
            $trainee = Trainee::findOrFail($id);
        }

        // Return the agreement view with the trainee data
        return view('Trainee.agreement', compact('trainee'));
    }

    public function showOwnAgreement()
    {
        // Get the currently authenticated trainee
        $trainee = Auth::guard('trainee')->user();

        if ($trainee) {
            // Ensure the trainee belongs to the current company
            $trainee = Trainee::where('company_id', app('currentCompanyId'))->findOrFail($trainee->id);

            // Return the agreement view with the trainee data
            return view('Trainee.trainee_agreement', compact('trainee'));
        }

        // If the trainee is not authenticated, redirect to login
        return redirect()->route('trainee.login')->withErrors('You are not logged in.');
    }

    public function downloadAgreement($id)
    {
        // Ensure the trainee belongs to the current company
        $trainee = Trainee::where('company_id', app('currentCompanyId'))->findOrFail($id);

        // Render the view with the trainee data
        $agreementContent = view('Trainee.agreement', ['trainee' => $trainee])->render();

        // Create a PDF from the content
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($agreementContent);

        // Download the PDF
        return $pdf->download("agreement_{$id}.pdf");
    }

    private function authorizeCompany(Trainee $trainee)
    {
        $companyId = app('currentCompanyId');
        if ($trainee->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}