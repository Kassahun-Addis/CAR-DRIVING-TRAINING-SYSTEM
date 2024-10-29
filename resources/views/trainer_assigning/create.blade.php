@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trainer Assigning</h1>

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
        <form action="{{ route('trainer_assigning.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">

                <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label for="trainer_name">Trainer Name:</label>
                        <select class="form-control" id="trainer_name" name="trainer_name" required>
                            <option value="">Select a Trainer</option>
                            @foreach($sortedTrainers as $trainer)
                                @if($trainer->training_type === 'Practical' || $trainer->training_type === 'Both')
                                    <option value="{{ $trainer->trainer_name }}"style="width: 100%;">
                                        {{ $trainer->trainer_name }} ({{ $trainerCounts[$trainer->trainer_name] ?? 0 }} Trainees)
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="trainee_name">Trainee Name:</label>
                        <select class="form-control" id="trainee_name" name="trainee_name" required>
                            <option value=""style="width: 100%;">Select a Trainee</option>
                            @foreach($trainees as $trainee)
                                <option value="{{ $trainee->full_name }}">{{ $trainee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Car Category:</label>
                        <input type="text" class="form-control" id="category_id" name="category_id" readonly>
                    </div>

                    <div class="form-group">
                        <label for="plate_no">Plate No:</label>
                        <input type="text" class="form-control" id="plate_no" name="plate_no" readonly>
                    </div>

                    <div class="form-group">
                        <label for="car_name">Car Name:</label>
                        <input type="text" class="form-control" id="car_name" name="car_name" readonly>
                    </div>

                    <div class="form-group">
                        <label for="total_time">Total Time:</label>
                        <input type="number" class="form-control" id="total_time" name="total_time" required>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainer_assigning.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>

<!-- Include jQuery and Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on the trainee_name and trainer_name dropdowns
        $('#trainee_name').select2({
            placeholder: "Select a Trainee",
            allowClear: true
        });

        $('#trainer_name').select2({
            placeholder: "Select a Trainer",
            allowClear: true
        });

        // Fetch car details when the trainer is selected
        $('#trainer_name').on('change', function() {
            var trainerName = $(this).val(); // Get the selected trainer name

            // Clear previous values
            $('#category_id').val('');
            $('#plate_no').val('');
            $('#car_name').val('');
            $('#trainee_name').empty().trigger('change'); // Clear trainee dropdown

            if (trainerName) {
                // Make an AJAX request to fetch the trainer details using the trainer name
                $.ajax({
                    url: '/trainers/' + encodeURIComponent(trainerName) + '/details',
                    type: 'GET',
                    success: function(data) {
                        // Fill the fields with the fetched data
                        $('#category_id').val(data.category);
                        $('#plate_no').val(data.plate_no);
                        $('#car_name').val(data.car_name);

                        // Fetch trainees based on the category
                        fetchTraineesByCategory(data.category);
                    },
                    error: function(error) {
                        console.error('Error fetching trainer details:', error);
                    }
                });
            }
        });

        // Function to fetch trainees based on category
        function fetchTraineesByCategory(category) {
            $.ajax({
                url: '/trainees/by-category/' + encodeURIComponent(category),
                type: 'GET',
                success: function(data) {
                    // Populate the trainee dropdown
                    $('#trainee_name').empty().append('<option value="">Select a Trainee</option>');
                    data.forEach(function(trainee) {
                        $('#trainee_name').append('<option value="' + trainee.full_name + '">' + trainee.full_name + '</option>');
                    });
                    $('#trainee_name').trigger('change');
                },
                error: function(error) {
                    console.error('Error fetching trainees:', error);
                }
            });
        }

        // Hide the success message after 3 seconds using plain JavaScript
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.transition = 'opacity 0.5s ease-out';
                successAlert.style.opacity = '0'; // Start fade out
                setTimeout(function() {
                    successAlert.style.display = 'none'; // Ensure it's removed from the document flow
                }, 500); // Wait for the fade-out transition to complete
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });
</script>
@endsection