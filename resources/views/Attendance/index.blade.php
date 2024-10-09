@extends('layouts.app')

@section('title', 'Attendance List')

@section('content')
<div class="container mt-5">
    <h2>Attendance List</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Start Time</th>
                <th>Finish Time</th>
                <th>Trainee Name</th>
                <th>Trainer Name</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->Date }}</td>
                    <td>{{ $attendance->StartTime }}</td>
                    <td>{{ $attendance->FinishTime }}</td>
                    <td>{{ $attendance->TraineeName }}</td>
                    <td>{{ $attendance->TrainerName }}</td>
                    <td>{{ $attendance->Status }}</td>
                    <td>{{ $attendance->Comments }}</td>
                    <td>
                        <!-- You can add actions like Edit or Delete here -->
                        <a href="{{ route('attendance.edit', $attendance->AttendanceID) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('attendance.destroy', $attendance->AttendanceID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">Add New Attendance</a>
        <a href="{{ route('home') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection