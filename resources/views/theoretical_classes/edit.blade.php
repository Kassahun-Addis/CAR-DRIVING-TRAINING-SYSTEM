@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Trainer Assignment</h1>

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
        <form action="{{ route('theoretical_class.update', $theoreticalClass->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">

                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $theoreticalClass->start_date }}" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $theoreticalClass->end_date }}" required>
                    </div>

                    <div class="form-group">
                        <label for="trainee_name">Trainee Name:</label>
                        <select class="form-control" id="trainee_name" name="trainee_name" required>
                            <option value="" style="width: 100%;">Select a Trainee</option>
                            @foreach($trainees as $trainee)
                                <option value="{{ $trainee->full_name }}" {{ $trainee->full_name == $theoreticalClass->trainee_name ? 'selected' : '' }}>
                                    {{ $trainee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
    <label for="class_name">Class Name:</label>
    <select class="form-control" id="class_name" name="class_name" required>
        <option value="">Select Class with Small No of Trainee</option>
        @foreach($sortedClasses as $class)
            <option value="{{ $class->class_name }}" 
                {{ (string)$class->class_name === (string)$theoreticalClass->class_name ? 'selected' : '' }}>
                {{ $class->class_name }} ({{ $traineeCounts[$class->class_name] ?? 0 }} Trainees)
            </option>
        @endforeach
    </select>
</div>

                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('theoretical_class.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>

    <style>
        #success-alert {
            opacity: 1; 
            transition: opacity 0.5s ease-out; 
        }
    </style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch the success alert element after the DOM has fully loaded
    var successAlert = document.getElementById('success-alert');

    // Log the success alert element to the console (after it has been defined)
    console.log('Script running, success alert:', successAlert);

    // Check if the success alert exists on the page
    if (successAlert) {
        // Fade out the alert after 3 seconds
        setTimeout(function() {
            successAlert.style.opacity = '0'; // Start fade out effect

            // After the fade-out completes, hide the element from view
            setTimeout(function() {
                successAlert.style.display = 'none'; // Hide the alert completely
            }, 500); // Match the fade-out transition duration (0.5 seconds)
        }, 3000); // Wait 3 seconds before starting fade out
    }
});
</script>
@endsection