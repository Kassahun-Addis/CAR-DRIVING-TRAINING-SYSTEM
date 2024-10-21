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
        <form action="{{ route('trainer_assigning.update', $trainer_assigning->assigning_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">

                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $trainer_assigning->start_date }}" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $trainer_assigning->end_date }}" required>
                    </div>

                    <div class="form-group">
                        <label for="trainee_name">Trainee Name:</label>
                        <input type="text" class="form-control" id="trainee_name" name="trainee_name" value="{{ $trainer_assigning->trainee_name }}" required>
                    </div>

                    <div class="form-group">
    <label for="trainer_name">Trainer Name:</label>
    <select class="form-control" id="trainer_name" name="trainer_name" required>
        <option value="">Select a Trainer</option>
        @foreach($sortedTrainers as $trainer)
            @if($trainer->training_type === 'Practical' || $trainer->training_type === 'Both')
                <option value="{{ $trainer->trainer_name }}" {{ $trainer->trainer_name == $trainer_assigning->trainer_name ? 'selected' : '' }}>
                    {{ $trainer->trainer_name }} ({{ $trainerCounts[$trainer->trainer_name] ?? 0 }} Trainees)
                </option>
            @endif
        @endforeach
    </select>
</div>

                    <div class="form-group">
                        <label for="category_id">Car Category:</label>
                        <input type="text" class="form-control" id="category_id" name="category_id" value="{{ $trainer_assigning->category }}" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="plate_no">Plate No:</label>
                        <input type="text" class="form-control" id="plate_no" name="plate_no" value="{{ $trainer_assigning->plate_no }}" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="car_name">Car Name:</label>
                        <input type="text" class="form-control" id="car_name" name="car_name" value="{{ $trainer_assigning->car_name }}" readonly required>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainer_assigning.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Fetch car details when the trainer is selected
    document.getElementById('trainer_name').addEventListener('change', function() {
        var selectedTrainer = this.value;

        if (selectedTrainer) {
            // Use the existing route for fetching car details
            fetch(`/trainers/${encodeURIComponent(selectedTrainer)}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('category_id').value = data.category;
                        document.getElementById('plate_no').value = data.plate_no;
                        document.getElementById('car_name').value = data.car_name;
                    } else {
                        // Clear the fields if no details found
                        document.getElementById('category_id').value = '';
                        document.getElementById('plate_no').value = '';
                        document.getElementById('car_name').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching car details:', error);
                });
        }
    });

    // Pre-fill the dropdown and other fields when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Pre-fill the dropdown with the selected trainer
        var selectedTrainer = "{{ $trainer_assigning->trainer_name }}";
        var trainerSelect = document.getElementById('trainer_name');

        // Set the selected trainer on page load
        if (selectedTrainer) {
            trainerSelect.value = selectedTrainer;

            // Fetch car details for the selected trainer
            fetch(`/trainers/${encodeURIComponent(selectedTrainer)}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('category_id').value = data.category || "{{ $trainer_assigning->category }}";
                        document.getElementById('plate_no').value = data.plate_no || "{{ $trainer_assigning->plate_no }}";
                        document.getElementById('car_name').value = data.car_name || "{{ $trainer_assigning->car_name }}";
                    } else {
                        document.getElementById('category_id').value = "{{ $trainer_assigning->category }}";
                        document.getElementById('plate_no').value = "{{ $trainer_assigning->plate_no }}";
                        document.getElementById('car_name').value = "{{ $trainer_assigning->car_name }}";
                    }
                })
                .catch(error => {
                    console.error('Error fetching car details:', error);
                });
        }

        // Hide the success message after 3 seconds
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            console.log('Success alert element found:', successAlert); // Check if the alert is found
            setTimeout(function() {
                console.log('Hiding success alert'); // Check if this line runs
                successAlert.style.transition = 'opacity 0.5s ease-out';
                successAlert.style.opacity = '0'; // Start fade out
                setTimeout(function() {
                    successAlert.style.display = 'none'; // Remove from the document
                }, 500); // Wait for the fade-out transition to complete
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });
</script>


@endsection