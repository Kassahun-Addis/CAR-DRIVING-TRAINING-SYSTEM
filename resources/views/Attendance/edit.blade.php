@extends('student.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="container mt-5">
    <h2>Edit Attendance</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-section">
        <form action="{{ route('attendance.update', $attendance->attendance_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="date" class="required">Date</label>
                        <input type="date" class="form-control" id="date" name="Date" value="{{ $attendance->Date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="required">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="StartTime" value="{{ $attendance->StartTime }}" required>
                    </div>
                    <div class="form-group">
                        <label for="finish_time" class="required">Finish Time</label>
                        <input type="time" class="form-control" id="finish_time" name="FinishTime" value="{{ $attendance->FinishTime }}" required>
                    </div>
                    <div class="form-group">
                        <label for="trainee_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="trainee_name" name="TraineeName" value="{{ $attendance->TraineeName }}" required>
                    </div>
                    <div class="form-group">
                        <label for="trainer_name" class="required">Trainer Name</label>
                        <input type="text" class="form-control" id="trainer_name" name="TrainerName" value="{{ $attendance->TrainerName }}" required>
                    </div>
                  

                    <div class="form-group">
    <label for="present" class="required">Mark Present</label>
    <div style="display: inline-flex; align-items: center;">
        <label for="present" style="margin: 0; padding-right: 10px;">Present?</label>
        <input type="checkbox" name="Present" id="present" value="Present" onchange="this.form.Status.value=this.checked ? 'Present' : 'Absent';" {{ $attendance->Status == 'Present' ? 'checked' : '' }}>
    </div>
</div>

<div class="form-group">
                        <label for="comment" class="required">Comments</label>
                        <input type="text" class="form-control" id="comment" name="comment" value="{{ $attendance->comment }}">
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>
@endsection