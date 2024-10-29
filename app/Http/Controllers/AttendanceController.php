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
    //     public function create()
    // {
    //     // Return the view to create a new attendance record
    //     return view('Attendance.attendance'); // Make sure this view exists
    // }

   

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

    Attendance::create([
        'date' => $request->date,
        'start_time' => $request->start_time,
        'finish_time' => $request->finish_time,
        'difference' => $request->difference,
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'status' => $request->status,
        'comment' => $request->comment,
        'trainee_id' => Auth::guard('trainee')->user()->id, // Ensure this line is present
        'latitude' => $latitude, // Store latitude (optional)
        'longitude' => $longitude, // Store longitude (optional)
        'location' => $placeName, // Store the specific place name
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
}


public function index(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('perPage', 10);

    $traineeId = null;
    $traineeName = null;
    $totalTime = 'N/A';

    // Determine the authenticated user and their role
    if (Auth::guard('web')->check()) {
        $traineeId = $request->input('trainee_id');
        $traineeName = $request->input('trainee_name');
    } elseif (Auth::guard('trainee')->check()) {
        $traineeId = Auth::guard('trainee')->user()->id;
        $traineeName = Auth::guard('trainee')->user()->full_name;
    } else {
        return redirect()->route('login')->with('error', 'Please log in to view attendance.');
    }

    // Fetch attendances based on the authenticated user's role
    $attendances = Attendance::when($traineeId, function ($query) use ($traineeId) {
            return $query->where('trainee_id', $traineeId);
        })
        ->when($traineeName, function ($query) use ($traineeName) {
            return $query->where('trainee_name', 'like', '%' . $traineeName . '%');
        })
        ->when($search, function ($query) use ($search) {
            return $query->where('date', 'like', '%' . $search . '%')
                         ->orWhere('status', 'like', '%' . $search . '%');
        })
        ->paginate($perPage);

    // Fetch total time from TrainerAssigning
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
            \Log::info('Trainer Assignment found:', [
                'trainee_name' => $attendance->trainee_name,
                'total_time' => $trainerAssignment->total_time
            ]);
            $attendance->start_date = $trainerAssignment->start_date;
            $attendance->end_date = $trainerAssignment->end_date;
            $attendance->category_id = $trainerAssignment->category_id;
            $attendance->plate_no = $trainerAssignment->plate_no;
            $attendance->total_time = $trainerAssignment->total_time; // Ensure this is fetched correctly
        } else {
            \Log::info('Trainer Assignment not found:', ['trainee_name' => $attendance->trainee_name]);
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
            return redirect()->route('login')->with('error', 'Please log in to edit attendance.');
        }

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
        return redirect()->route('login')->with('error', 'Please log in to edit attendance.');
    }

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
    $attendance->delete();

    // Redirect to the index page with a success message
    return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
}

// public function showAttendance($trainee_id)
// {
//     // Get the authenticated user
//     $user = Auth::guard('trainee')->user();

//     // Check if the user is an admin
//     if ($user && $user->isAdmin()) {
//         // Admin can view any trainee's attendance
//         $attendances = Attendance::where('trainee_id', $trainee_id)->get();
//     } elseif ($user && $user->id == $trainee_id) {
//         // Trainee can only view their own attendance
//         $attendances = Attendance::where('trainee_id', $trainee_id)->get();
//     } else {
//         // Redirect if the user is not authorized
//         return redirect()->route('attendance.index')->with('error', 'Unauthorized access.');
//     }

//     return view('Attendance.show', compact('attendances'));
// }


public function showTraineeAttendance($id)
{
    $attendances = Attendance::where('trainee_id', $id)->paginate(10); // Adjust to your column name
    $trainee = Trainee::findOrFail($id); // Get the trainee details

    return view('Attendance.show', compact('attendances', 'trainee'));
}


}
