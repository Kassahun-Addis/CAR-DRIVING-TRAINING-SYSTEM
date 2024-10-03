<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('Attendance.attendance'); // The path to your form view
    }

    public function store(Request $request)
{
    // Log the incoming request data
    \Log::info('Storing attendance data:', $request->all());

    // Validate the incoming request data
    $request->validate([
        'asset_name' => 'required|string|max:255',
        'category' => 'required|string',
        'purchase_price' => 'required|numeric',
        'status' => 'required|string',
        'serial_no' => 'nullable|string',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable|string',
        'department' => 'required|string',
        'purchase_date' => 'required|date',
        'last_maintenance_date' => 'nullable|date',
        'remark' => 'nullable|string',
    ]);

    // Create a new Trainee entry using the AssetModel
    // Explicitly map the form data to the correct columns
    $asset = AttendanceController::create([
        'asset_name' => $request->input('asset_name'),
        'category' => $request->input('category'),
        'purchase_price' => $request->input('purchase_price'),
        'status' => $request->input('status'),
        'serial_no' => $request->input('serial_no'),
        'description' => $request->input('description'),
        'assigned_to' => $request->input('assigned_to'),
        'department' => $request->input('department'),
        'purchase_date' => $request->input('purchase_date'),
        'last_maintenance_date' => $request->input('last_maintenance_date'),
        'remark' => $request->input('remark'),
    ]);

    // Log the newly created Trainee
    \Log::info('New asset created:', $asset->toArray());

    // Redirect or return response
    return redirect()->route('trainee.show')->with('success', 'Attendance added successfully.');
}
}
