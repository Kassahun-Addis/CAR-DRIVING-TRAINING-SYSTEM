@extends('student.app')

@section('title', 'Attendance - Add New')

@section('content')

<style>
/* Reduce the font size of h2 and hide the icon on small devices */
@media (max-width: 768px) {
    h2 {
        font-size: 1.25rem; /* Reduce font size for h2 */
    }

    .btn-custom i {
        display: none; /* Hide the arrow icon */
    }
}
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Attendance, Add New</h2>
        <a href="/home" class="btn btn-secondary btn-custom">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    <div class="form-section">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="Absent"> <!-- Default status -->
            <input type="hidden" name="trainee_id" value="{{ Auth::guard('trainee')->user()->id }}"> <!-- Hidden field for Trainee ID -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <input type="hidden" id="location" name="location"> <!-- Hidden field for location -->

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="date" class="required">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="required">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="finish_time" class="required">Finish Time</label>
                        <input type="time" class="form-control" id="finish_time" name="finish_time" required>
                    </div>
                    <div class="form-group">
                        <label for="trainee_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="trainee_name" name="trainee_name" value="{{ Auth::guard('trainee')->user()->full_name }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="trainer_name" class="required">Trainer Name</label>
                        <input type="text" class="form-control" id="trainer_name" name="trainer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="present" class="required">Mark Present</label>
                        <div style="display: inline-flex; align-items: center;">
                            <label for="present" style="margin: 0; padding-right: 10px;">Present?</label>
                            <input type="checkbox" name="present" id="present" value="Present" 
                                   onchange="this.form.status.value=this.checked ? 'Present' : 'Absent';">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment" class="required">Comment</label>
                        <input type="text" class="form-control" id="comment" name="comment">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                @if (!request()->routeIs('attendance.index'))
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
               @endif
            </div>
        </form>
    </div>
</div>
@endsection