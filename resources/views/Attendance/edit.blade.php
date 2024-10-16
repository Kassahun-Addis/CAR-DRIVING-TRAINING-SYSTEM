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
                        <input type="date" class="form-control" id="date" name="date" value="{{ $attendance->date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="required">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $attendance->start_time }}" required>
                    </div>
                    <div class="form-group">
                        <label for="finish_time" class="required">Finish Time</label>
                        <input type="time" class="form-control" id="finish_time" name="finish_time" value="{{ $attendance->finish_time }}" required>
                    </div>
                    <div class="form-group">
                        <label for="trainee_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="trainee_name" name="trainee_name" value="{{ $attendance->trainee_name }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="trainer_name" class="required">Trainer Name</label>
                        <input type="text" class="form-control" id="trainer_name" name="trainer_name" value="{{ $attendance->trainer_name }}" required>
                    </div>
                  

                    <div class="form-group">
    <label for="present" class="required">Mark Present</label>
    <div style="display: inline-flex; align-items: center;">
        <label for="present" style="margin: 0; padding-right: 10px;">Present?</label>
        <input type="checkbox" name="status" id="present" value="Present" onchange="this.form.comment.value=''; this.form.status.value=this.checked ? 'Present' : 'Absent';" {{ $attendance->status == 'Present' ? 'checked' : '' }}>
    </div>
</div>



<div class="form-group">
                        <label for="comment" class="required">Comments</label>
                        <input type="text" class="form-control" id="comment" name="comment" value="{{ $attendance->comment }}">
                    </div>

                </div>
                </div>

            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <!-- <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-custom">Back to list</a> -->
                <a href="{{ route('attendance.index', ['trainee_id' => $trainee->id, 'trainee_name' => $trainee->full_name]) }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>
@endsection