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
            <label for="trainee_name">Trainee Name:</label>
            <input type="text" class="form-control" id="trainee_name" name="trainee_name" required>
        </div>

        <div class="form-group">
            <label for="trainer_name">Trainer Name:</label>
            <input type="text" class="form-control" id="trainer_name" name="trainer_name" required>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>

        <div class="form-group">
            <label for="category_id">Car Category:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                @foreach($carCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->car_category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
    <label for="plate_no">Plate No:</label>
    <select class="form-control" id="plate_no" name="plate_no" required>
        <option value="">Select a plate number</option>
        <!-- Options will be populated dynamically based on the category -->
    </select>
</div>

        <div class="form-group">
            <label for="car_name">Car Name:</label>
            <input type="text" class="form-control" id="car_name" name="car_name" required>
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
    document.getElementById('category_id').addEventListener('change', function() {
        var categoryId = this.value;
        var plateSelect = document.getElementById('plate_no');
        plateSelect.innerHTML = ''; // Clear previous options

        if (categoryId) {
            fetch(`/car-category/${categoryId}/plates`) // Ensure this matches your route
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    Object.keys(data).forEach(function(id) {
                        var option = document.createElement('option');
                        option.value = id;
                        option.textContent = data[id];
                        plateSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching plates:', error);
                });
        }
    });
</script>

@endsection