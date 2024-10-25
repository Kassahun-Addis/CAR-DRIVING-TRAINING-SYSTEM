@extends('student.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="container mt-5">
    <h2>Edit Attendance</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-section">
        <form action="{{ route('attendance.update', $attendance->attendance_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="date" class="required">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $attendance->date }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="required">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $attendance->start_time }}" required onchange="calculateDifference()">
                    </div>
                    <div class="form-group">
                        <label for="finish_time" class="required">Finish Time</label>
                        <input type="time" class="form-control" id="finish_time" name="finish_time" value="{{ $attendance->finish_time }}" required onchange="calculateDifference()">
                    </div>
                    <div class="form-group">
                        <label for="difference" class="required">Difference</label>
                        <input type="text" class="form-control" id="difference" name="difference" value="{{ $attendance->difference }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="trainee_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="trainee_name" name="trainee_name" value="{{ $attendance->trainee_name }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="trainer_name" class="required">Trainer Name</label>
                        <input type="text" class="form-control" id="trainer_name" name="trainer_name" value="{{ $attendance->trainer_name }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="present"> Mark Present</label>
                        <div style="display: inline-flex; align-items: center;">
                            <label for="present" style="margin: 0; padding-right: 10px;">Present?</label>
                            <input type="checkbox" name="status_checkbox" id="present" value="Present" onchange="updateStatus(this)" {{ $attendance->status == 'Present' ? 'checked' : '' }}>
                        </div>
                        <input type="hidden" name="status" value="Absent"> <!-- Hidden field default to Absent -->
                    </div>
                    <div class="form-group">
                        <label for="comment" class="required">Comments</label>
                        <input type="text" class="form-control" id="comment" name="comment" value="{{ $attendance->comment }}">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <a href="{{ route('attendance.index', ['trainee_id' => $trainee->id, 'trainee_name' => $trainee->full_name]) }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>

<script>
function calculateDifference() {
    const startTime = document.getElementById('start_time').value;
    const finishTime = document.getElementById('finish_time').value;

    if (startTime && finishTime) {
        const start = new Date(`1970-01-01T${startTime}Z`);
        const finish = new Date(`1970-01-01T${finishTime}Z`);
        const diff = (finish - start) / (1000 * 60 * 60); // Difference in hours

        document.getElementById('difference').value = diff.toFixed(2) + ' hours';
    } else {
        document.getElementById('difference').value = '';
    }
}

// Call calculateDifference on page load to set the initial value
window.onload = function() {
    calculateDifference();
}

function updateStatus(checkbox) {
    // If the checkbox is checked, set status to 'Present'; otherwise, it remains 'Absent'
    checkbox.form.status.value = checkbox.checked ? 'Present' : 'Absent';
}
</script>

@endsection