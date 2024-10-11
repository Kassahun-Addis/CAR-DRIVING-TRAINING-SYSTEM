<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
        public function create()
    {
        // Return the view to create a new attendance record
        return view('Attendance.attendance'); // Make sure this view exists
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'Date' => 'required|date',
            'StartTime' => 'required',
            'FinishTime' => 'required',
            'TraineeName' => 'required|string',
            'TrainerName' => 'required|string',
            'Status' => 'required|in:Present,Absent',
            'comment' => 'nullable|string',
        ]);

        // Create a new attendance record
        Attendance::create([
            'Date' => $request->Date,
            'StartTime' => $request->StartTime,
            'FinishTime' => $request->FinishTime,
            'TraineeName' => $request->TraineeName,
            'TrainerName' => $request->TrainerName,
            'Status' => $request->Status,
            'comment' => $request->comment,

        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search term
        $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10

        // Query the banks with search and pagination
         $attendances = Attendance::when($search, function ($query) use ($search) {
            return $query->where('Date', 'like', '%' . $search . '%')
                        ->orWhere('Status', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return view('Attendance.index', compact('attendances'));
   }

    public function edit($id)
{
    // Retrieve the attendance record by its ID
    $attendance = Attendance::findOrFail($id);

    // Return the edit view with the attendance record
    return view('Attendance.edit', compact('attendance')); // Make sure this view exists
}

public function update(Request $request, $id)
{
    // Validate the incoming request data
    $request->validate([
        'Date' => 'required|date',
        'StartTime' => 'required',
        'FinishTime' => 'required',
        'TraineeName' => 'required|string',
        'TrainerName' => 'required|string',
        'comment' => 'nullable|string', // Changed to nullable
    ]);

    // Find the attendance record and update it
    $attendance = Attendance::findOrFail($id);
    $attendance->update([
        'Date' => $request->Date,
        'StartTime' => $request->StartTime,
        'FinishTime' => $request->FinishTime,
        'TraineeName' => $request->TraineeName,
        'TrainerName' => $request->TrainerName,
        'Status' => $request->has('Present') ? 'Present' : 'Absent', // Updated status handling
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
