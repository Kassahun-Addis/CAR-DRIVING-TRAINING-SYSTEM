@extends('student.app')

@section('title', 'Trainee Attendance')

@section('content')
<div class="container mt-5">
    <h2>Your Attendance Records</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Finish Time</th>
                    <th>Trainer Name</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->date }}</td>
                        <td>{{ $attendance->start_time }}</td>
                        <td>{{ $attendance->finish_time }}</td>
                        <td>{{ $attendance->trainer_name }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->comment }}</td>
                        <td>
                            <a href="{{ route('attendance.edit', $attendance->attendance_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('attendance.destroy', $attendance->attendance_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this attendance record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
