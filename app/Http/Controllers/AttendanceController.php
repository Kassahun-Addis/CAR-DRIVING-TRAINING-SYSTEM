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
        ]);

        // Create a new attendance record
        Attendance::create([
            'Date' => $request->Date,
            'StartTime' => $request->StartTime,
            'FinishTime' => $request->FinishTime,
            'TraineeName' => $request->TraineeName,
            'TrainerName' => $request->TrainerName,
            'Status' => $request->Status,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

        public function index()
    {
        // Fetch all attendance records
        $attendances = Attendance::all(); // You might want to paginate or filter this in a real application

        // Return the view with the attendance records
        return view('Attendance.index', compact('attendances')); // Make sure this view exists
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
        'Status' => 'required|in:Present,Absent',
    ]);

    // Find the attendance record and update it
    $attendance = Attendance::findOrFail($id);
    $attendance->update([
        'Date' => $request->Date,
        'StartTime' => $request->StartTime,
        'FinishTime' => $request->FinishTime,
        'TraineeName' => $request->TraineeName,
        'TrainerName' => $request->TrainerName,
        'Status' => $request->Status,
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
}


}
