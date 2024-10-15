<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
//use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
        public function create()
    {
        // Return the view to create a new attendance record
        return view('Attendance.attendance'); // Make sure this view exists
    }

    private function validateAttendance(Request $request)
{
    return $request->validate([
        'date' => 'required|date',
        'start_time' => 'required',
        'finish_time' => 'required',
        'trainee_name' => 'required|string',
        'trainer_name' => 'required|string',
        'status' => 'required|in:Present,Absent',
        'comment' => 'nullable|string',
    ]);
}
public function store(Request $request)
{
    $this->validateAttendance($request);

    Attendance::create([
        'date' => $request->date,
        'start_time' => $request->start_time,
        'finish_time' => $request->finish_time,
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'status' => $request->status,
        'comment' => $request->comment,
        'trainee_id' => Auth::guard('trainee')->user()->id, // Ensure this line is present
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
}

public function index(Request $request)
{
    $search = $request->input('search'); // Get the search term
    $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

    // Query the attendances with search and pagination, filtering by the authenticated trainee
    $attendances = Attendance::where('trainee_id', Auth::guard('trainee')->user()->id) // Filter by trainee ID
        ->when($search, function ($query) use ($search) {
            return $query->where('date', 'like', '%' . $search . '%')
                         ->orWhere('status', 'like', '%' . $search . '%');
        })->paginate($perPage);

    return view('Attendance.index', compact('attendances'));
}

public function edit($id)
{
    // Retrieve the attendance record by its ID
    $attendance = Attendance::where('attendance_id', $id)
        ->where('trainee_id', Auth::guard('trainee')->user()->id) // Ensure the trainee owns the record
        ->firstOrFail();

    // Return the edit view with the attendance record
    return view('Attendance.edit', compact('attendance')); // Make sure this view exists
}

public function update(Request $request, $id)
{
    $this->validateAttendance($request);

    $attendance = Attendance::findOrFail($id);
    $attendance->update([
        'date' => $request->date,
        'start_time' => $request->start_time,
        'finish_time' => $request->finish_time,
        'trainee_name' => $request->trainee_name,
        'trainer_name' => $request->trainer_name,
        'status' => $request->status,
        'comment' => $request->comment,
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
}

public function destroy($id)
{
    // Find the Attendance by id and delete it
    $attendance = Attendance::findOrFail($id);
    $attendance->delete();

    // Redirect to the index page with a success message
    return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
}

}
