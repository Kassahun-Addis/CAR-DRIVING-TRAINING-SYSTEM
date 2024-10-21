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
                        <label for="trainee_name">Trainee Name:</label>
                        <input type="text" class="form-control" id="trainee_name" name="trainee_name" required>
                    </div>

                    <div class="form-group">
                        <label for="trainer_name">Trainer Name:</label>
                        <select class="form-control" id="trainer_name" name="trainer_name" required>
                            <option value="">Select a Trainer</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->trainer_name }}</option>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Add an event listener for when a trainer is selected
    $('#trainer_name').on('change', function() {
        var trainerId = $(this).val(); // Get the selected trainer ID

        // Clear previous values
        $('#category_id').val('');
        $('#plate_no').val('');
        $('#car_name').val('');

        if (trainerId) {
            // Make an AJAX request to fetch the trainer details
            $.ajax({
                url: '/trainers/' + trainerId + '/details', // API endpoint to fetch trainer details
                type: 'GET',
                success: function(data) {
                    // Fill the fields with the fetched data
                    $('#category_id').val(data.category);
                    $('#plate_no').val(data.plate_no);
                    $('#car_name').val(data.car_name);
                },
                error: function(error) {
                    console.error('Error fetching trainer details:', error);
                }
            });
        }
    });
</script>
@endsection