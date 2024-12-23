<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Trainee;
use App\Models\TrainerAssigning;
use Illuminate\Http\Request;
//use App\Http\Controllers\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use DateTime;

class AttendanceController extends Controller
{
    public function create()
    {
        $traineeName = Auth::guard('trainee')->user()->full_name;
        $trainerAssigning = TrainerAssigning::where('trainee_name', $traineeName)->first();

        $trainerName = $trainerAssigning ? $trainerAssigning->trainer_name : null;

        return view('Attendance.attendance', compact('trainerName'));
    }

    private function validateAttendance(Request $request)
    {
        return $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'finish_time' => 'required',
            'difference' => 'required',
            'trainee_name' => 'required|string',
            'trainer_name' => 'required|string',
            'status' => 'nullable|in:Present,Absent',
            'comment' => 'nullable|string',
        ]);
    }

    public function store(Request $request)
{
    try {
        $this->validateAttendance($request);

        // Get the user's IP address
        $ip = $request->ip();

        // Check for localhost
        if ($ip === '127.0.0.1') {
            $latitude = null; // or a default value
            $longitude = null; // or a default value
            $placeName = 'Localhost'; // or a default value
        } else {
            // Use an IP geolocation service to get the location
            $locationData = file_get_contents("https://ipinfo.io/{$ip}/json");
            $location = json_decode($locationData);

            // Log the location data for debugging
            \Log::info('IP Geolocation Response:', (array)$location);

            // Initialize variables
            $latitude = null;
            $longitude = null;
            $placeName = 'Unknown Location'; // Default value

            // Check if the location data is valid
            if (isset($location->loc)) {
                // Capture latitude and longitude
                $latitude = explode(',', $location->loc)[0];
                $longitude = explode(',', $location->loc)[1];

                // Log latitude and longitude
                \Log::info('Latitude: ' . $latitude . ', Longitude: ' . $longitude);

                // Use a reverse geocoding service to get the place name
                $reverseGeocodeData = file_get_contents("https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json");
                $reverseLocation = json_decode($reverseGeocodeData);

                // Log the reverse geocoding response
                \Log::info('Reverse Geocoding Response:', (array)$reverseLocation);

                // Get the specific place name
                $placeName = $reverseLocation->address->suburb ?? $reverseLocation->address->city ?? $reverseLocation->address->town ?? 'Unknown Location';
            }
        }

        // Get the current company ID from the application context
        $companyId = app('currentCompanyId');

        // Ensure the trainee is authenticated
        $trainee = Auth::guard('trainee')->user();
        if (!$trainee) {
            return redirect()->route('trainee.login')->with('error', 'Please log in to record attendance.');
        }

        // Attempt to create the attendance record
        $attendance = Attendance::create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'difference' => $request->difference,
            'trainee_name' => $request->trainee_name,
            'trainer_name' => $request->trainer_name,
            'status' => $request->status,
            'comment' => $request->comment,
            'trainee_id' => $trainee->id, // Ensure this line is present
            'latitude' => $latitude, // Store latitude (optional)
            'longitude' => $longitude, // Store longitude (optional)
            'location' => $placeName, // Store the specific place name
            'company_id' => $companyId, // Set company_id
        ]);

        \Log::info('Attendance record created successfully:', $attendance->toArray());

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    } catch (\Exception $e) {
        \Log::error('Error saving attendance record:', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors('Failed to record attendance. Please try again.');
        }
    }

    public function index(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('perPage', 10);

    $traineeId = $request->input('trainee_id');
    $traineeName = $request->input('trainee_name');
    $totalTime = 'N/A';

    // Determine the authenticated user and their role
    if (Auth::guard('web')->check()) {
        // Admin is viewing the attendance
    } elseif (Auth::guard('trainee')->check()) {
        // Trainee is viewing their own attendance
        $traineeId = Auth::guard('trainee')->user()->id;
        $traineeName = Auth::guard('trainee')->user()->full_name;
    } else {
        \Log::error('Unauthorized access attempt to index', [
            'user_id' => Auth::id(),
            'message' => 'User is not authenticated',
        ]);
        return redirect()->route('trainee.login')->with('error', 'Please log in to view attendance.');
    }

    // Get the current company ID from the application context
    $companyId = app('currentCompanyId');

    try {
        // Fetch attendances based on the authenticated user's role and company
        $attendances = Attendance::where('company_id', $companyId)
        ->when($traineeId, function ($query) use ($traineeId) {
            // Restrict results to the specific trainee's attendance
            return $query->where('trainee_id', $traineeId);
        })
        ->when($traineeName && !$traineeId, function ($query) use ($traineeName) {
            // Apply the trainee name filter only if traineeId is not specified (like when an admin is viewing)
            return $query->where('trainee_name', 'like', '%' . $traineeName . '%');
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('trainee_name', 'like', '%' . $search . '%')
                    ->orWhere('trainer_name', 'like', '%' . $search . '%')
                    ->orWhere('comment', 'like', '%' . $search . '%');
            });
        })
        ->paginate($perPage);
            

        // Calculate total time for the trainee if traineeName is provided
        if ($traineeName) {
            $trainerAssignment = TrainerAssigning::where('trainee_name', $traineeName)->first();
            if ($trainerAssignment) {
                $totalTime = $trainerAssignment->total_time;
            }
        }

        // Transform attendance records to include additional information
        $attendances->getCollection()->transform(function ($attendance) {
            $start = new DateTime($attendance->start_time);
            $finish = new DateTime($attendance->finish_time);
            $interval = $start->diff($finish);
            $attendance->difference_in_hours = $interval->h + ($interval->i / 60); // Convert to hours

            $trainerAssignment = TrainerAssigning::where('trainee_name', $attendance->trainee_name)->first();
            
            if ($trainerAssignment) {
                $attendance->start_date = $trainerAssignment->start_date;
                $attendance->end_date = $trainerAssignment->end_date;
                $attendance->category_id = $trainerAssignment->category_id;
                $attendance->plate_no = $trainerAssignment->plate_no;
                $attendance->total_time = $trainerAssignment->total_time; // Ensure this is fetched correctly
            } else {
                $attendance->start_date = 'N/A';
                $attendance->end_date = 'N/A';
                $attendance->category_id = 'N/A';
                $attendance->plate_no = 'N/A';
                $attendance->total_time = 'N/A'; // Default value if not found
            }

            // Fetch the trainee's phone number
            $trainee = Trainee::where('full_name', $attendance->trainee_name)->first();
            $attendance->trainee_phone = $trainee ? $trainee->phone_no : 'N/A';

            return $attendance;
        });

        return view('Attendance.index', compact('attendances', 'totalTime'));
    } catch (\Exception $e) {
        \Log::error('Error fetching attendance records in index', [
            'error_message' => $e->getMessage(),
            'user_id' => Auth::id(),
        ]);
        return redirect()->back()->withErrors('Failed to fetch attendance records. Please try again.');
    }
}

public function adminIndex(Request $request)
{
    // Log the entry into the method
    \Log::info('Entering AttendanceController@adminIndex', [
        'user_id' => Auth::id(),
        'search' => $request->input('search'),
        'perPage' => $request->input('perPage', 10),
        'trainee_id' => $request->input('trainee_id'),
        'trainee_name' => $request->input('trainee_name'),
    ]);

    $search = $request->input('search');
    $perPage = $request->input('perPage', 10);

    $traineeId = $request->input('trainee_id');
    $traineeName = $request->input('trainee_name');
    $totalTime = 'N/A';

    // Ensure the user is an admin
    if (!Auth::guard('web')->check()) {
        \Log::error('Unauthorized access attempt to adminIndex', [
            'user_id' => Auth::id(),
            'message' => 'User is not an admin',
        ]);
        return redirect()->route('admin.login')->with('error', 'Please log in as an admin to view attendance.');
    }

    // Get the current company ID from the application context
    $companyId = app('currentCompanyId');
    \Log::info('Current company ID retrieved', ['company_id' => $companyId]);

    try {
        // Fetch attendances based on the authenticated user's role and company
        $attendances = Attendance::where('company_id', $companyId)
            ->when($traineeId, function ($query) use ($traineeId) {
                return $query->where('trainee_id', $traineeId);
            })
            ->when($traineeName, function ($query) use ($traineeName) {
                return $query->where('trainee_name', 'like', '%' . $traineeName . '%');
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('date', 'like', '%' . $search . '%')
                             ->orWhere('status', 'like', '%' . $search . '%')
                             ->orWhere('trainee_name', 'like', '%' . $search . '%')
                             ->orWhere('trainer_name', 'like', '%' . $search . '%')
                             ->orWhere('comment', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);

        \Log::info('Attendance records fetched successfully', [
            'total_records' => $attendances->total(),
        ]);

        // Calculate total time for the trainee if traineeName is provided
        if ($traineeName) {
            $trainerAssignment = TrainerAssigning::where('trainee_name', $traineeName)->first();
            if ($trainerAssignment) {
                $totalTime = $trainerAssignment->total_time;
            }
        }

        // Transform attendance records to include additional information
        $attendances->getCollection()->transform(function ($attendance) {
            $start = new DateTime($attendance->start_time);
            $finish = new DateTime($attendance->finish_time);
            $interval = $start->diff($finish);
            $attendance->difference_in_hours = $interval->h + ($interval->i / 60); // Convert to hours

            $trainerAssignment = TrainerAssigning::where('trainee_name', $attendance->trainee_name)->first();
            
            if ($trainerAssignment) {
                $attendance->start_date = $trainerAssignment->start_date;
                $attendance->end_date = $trainerAssignment->end_date;
                $attendance->category_id = $trainerAssignment->category_id;
                $attendance->plate_no = $trainerAssignment->plate_no;
                $attendance->total_time = $trainerAssignment->total_time;
            } else {
                $attendance->start_date = 'N/A';
                $attendance->end_date = 'N/A';
                $attendance->category_id = 'N/A';
                $attendance->plate_no = 'N/A';
                $attendance->total_time = 'N/A';
            }

            // Fetch the trainee's phone number
            $trainee = Trainee::where('full_name', $attendance->trainee_name)->first();
            $attendance->trainee_phone = $trainee ? $trainee->phone_no : 'N/A';

            return $attendance;
        });

        return view('Attendance.admin_index', compact('attendances', 'totalTime'));
    } catch (\Exception $e) {
        \Log::error('Error fetching attendance records in adminIndex', [
            'error_message' => $e->getMessage(),
            'user_id' => Auth::id(),
        ]);
        return redirect()->back()->withErrors('Failed to fetch attendance records. Please try again.');
    }
}

    public function edit($id)
    {
        try {
            // Check if the user is an admin or a trainee
            if (Auth::guard('web')->check()) {
                // Admin is logged in; can edit any attendance record
                $attendance = Attendance::where('attendance_id', $id)->firstOrFail();
                // Optionally get the trainee details if needed
                $trainee = Trainee::find($attendance->trainee_id);
            } elseif (Auth::guard('trainee')->check()) {
                // Trainee is logged in; can only edit their own attendance record
                $attendance = Attendance::where('attendance_id', $id)
                    ->where('trainee_id', Auth::guard('trainee')->user()->id) // Ensure the trainee owns the record
                    ->firstOrFail();
                // Get the logged-in trainee
                $trainee = Auth::guard('trainee')->user();
            } else {
                return redirect()->route('trainee.login')->with('error', 'Please log in to edit attendance.');
            }

            // Ensure the attendance belongs to the current company
            $this->authorizeCompany($attendance);

            // Log the successful retrieval of the attendance record
            \Log::info('Retrieved Attendance record for editing:', [
                'attendance_id' => $attendance->attendance_id,
                'user_id' => Auth::id(), // Logs the ID of the logged-in user (admin or trainee)
            ]);

            // Return the edit view with the attendance record and trainee information
            return view('Attendance.edit', compact('attendance', 'trainee')); // Make sure this view exists
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log the error if the record is not found
            \Log::error('Attendance record not found for editing:', [
                'attendance_id' => $id,
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
            ]);

            // Redirect back with an error message
            return redirect()->back()->withErrors('Attendance record not found.');
        } catch (\Exception $e) {
            // Log any other exceptions
            \Log::error('An error occurred while retrieving the attendance record for editing:', [
                'attendance_id' => $id,
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
            ]);

            // Redirect back with a generic error message
            return redirect()->back()->withErrors('An error occurred. Please try again later.');
        }
    }

    public function update(Request $request, $id)
    {
        \Log::info('Update method called with request:', $request->all());
        // Validate the request data
        $this->validateAttendance($request);

        // Retrieve the attendance record
        if (Auth::guard('web')->check()) {
            $attendance = Attendance::findOrFail($id);
        } elseif (Auth::guard('trainee')->check()) {
            $attendance = Attendance::where('attendance_id', $id)
                ->where('trainee_id', Auth::guard('trainee')->user()->id)
                ->firstOrFail();
        } else {
            return redirect()->route('admin.login')->with('error', 'Please log in to edit attendance.');
        }

        // Ensure the attendance belongs to the current company
        $this->authorizeCompany($attendance);

        // Update the attendance record
        $attendance->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'difference' => $request->difference,
            'trainee_name' => $request->trainee_name,
            'trainer_name' => $request->trainer_name,
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        // Redirect to the attendance index with parameters
        return redirect()->route('attendance.index', [
            'trainee_id' => $attendance->trainee_id,
            'trainee_name' => $attendance->trainee_name,
        ])->with('success', 'Attendance updated successfully.');
    }

    public function destroy($id)
    {
        // Find the Attendance by id and delete it
        $attendance = Attendance::findOrFail($id);

        // Ensure the attendance belongs to the current company
        $this->authorizeCompany($attendance);

        $attendance->delete();

        // Redirect to the index page with a success message
        return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
    }

    public function showTraineeAttendance($id)
    {
        \Log::info('Show trainee attendance method called with ID:', ['id' => $id]);
        // Check if the authenticated user is a trainee and matches the requested trainee ID
        if (Auth::guard('trainee')->check() && Auth::guard('trainee')->user()->id == $id) {
            $attendances = Attendance::where('trainee_id', $id)->paginate(10);
            $trainee = Trainee::findOrFail($id);

            return view('Attendance.show', compact('attendances', 'trainee'));
        } else {
            // Redirect with an error if the user is not authorized
            return redirect()->route('attendance.index')->with('error', 'Unauthorized access.');
        }
    }

    private function authorizeCompany(Attendance $attendance)
    {
        $companyId = app('currentCompanyId');
        if ($attendance->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}