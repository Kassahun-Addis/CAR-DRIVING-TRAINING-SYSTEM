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
                        <label for="trainee_name">Trainee Name:</label>
                        <select class="form-control" id="trainee_name" name="trainee_name" required>
                            <option value="" style="width: 100%;">Select a Trainee</option>
                            @foreach($trainees as $trainee)
                                <option value="{{ $trainee->full_name }}" {{ trim(strtolower($trainee->full_name)) == trim(strtolower($trainer_assigning->trainee_name)) ? 'selected' : '' }}>
                                    {{ $trainee->full_name }}
                                </option>
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
                    
                    <div class="form-group">
                        <label for="total_time" class="required">Total Time</label>
                        <input type="number" class="form-control" id="total_time" name="total_time" value="{{ $trainer_assigning->total_time }}" required>
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

    <style>
        #success-alert {
            opacity: 1; 
            transition: opacity 0.5s ease-out; 
        }
    </style>
<script>
   // Fetch car details when the trainer is selected
   document.addEventListener('DOMContentLoaded', function() {
    var successAlert = document.getElementById('success-alert');

    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 3000);
    }

    var trainerSelect = document.getElementById('trainer_name');
    var traineeSelect = document.getElementById('trainee_name');

    trainerSelect.addEventListener('change', function() {
        var selectedTrainer = this.value;

        if (selectedTrainer) {
            fetch(`/trainers/${encodeURIComponent(selectedTrainer)}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('category_id').value = data.category;
                        document.getElementById('plate_no').value = data.plate_no;
                        document.getElementById('car_name').value = data.car_name;

                        // Fetch trainees based on the category
                        fetchTraineesByCategory(data.category);
                    } else {
                        document.getElementById('category_id').value = '';
                        document.getElementById('plate_no').value = '';
                        document.getElementById('car_name').value = '';
                        traineeSelect.innerHTML = '<option value="">Select a Trainee</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching car details:', error);
                });
        }
    });

    function fetchTraineesByCategory(category) {
        fetch(`/trainees/by-category/${encodeURIComponent(category)}`)
            .then(response => response.json())
            .then(data => {
                traineeSelect.innerHTML = '<option value="">Select a Trainee</option>';
                data.forEach(function(trainee) {
                    traineeSelect.innerHTML += `<option value="${trainee.full_name}">${trainee.full_name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error fetching trainees:', error);
            });
    }

    // Pre-fill the dropdown and other fields when the page loads
    var selectedTrainer = "{{ $trainer_assigning->trainer_name }}";
    if (selectedTrainer) {
        trainerSelect.value = selectedTrainer;
        fetch(`/trainers/${encodeURIComponent(selectedTrainer)}/details`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('category_id').value = data.category || "{{ $trainer_assigning->category }}";
                    document.getElementById('plate_no').value = data.plate_no || "{{ $trainer_assigning->plate_no }}";
                    document.getElementById('car_name').value = data.car_name || "{{ $trainer_assigning->car_name }}";

                    // Fetch trainees based on the category
                    fetchTraineesByCategory(data.category || "{{ $trainer_assigning->category }}");
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
});
</script>
@endsection