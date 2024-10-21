@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Trainer</h1>

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
        <form action="{{ route('trainers.update', $trainer) }}" method="POST"> <!-- Use the model instance -->
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">

                    <div class="form-group">
                        <label for="trainer_name">Trainer Name:</label>
                        <input type="text" class="form-control" id="trainer_name" name="trainer_name" value="{{ $trainer->trainer_name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $trainer->phone_number }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $trainer->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience (in years):</label>
                        <input type="number" class="form-control" id="experience" name="experience" value="{{ $trainer->experience }}" required>
                    </div>

                    <div class="form-group">
                        <label for="training_type">Training Type:</label>
                        <select name="training_type" id="training_type" class="form-control" required>
                            <option value="Theoretical" {{ $trainer->training_type == 'Theoretical' ? 'selected' : '' }}>Theoretical</option>
                            <option value="Practical" {{ $trainer->training_type == 'Practical' ? 'selected' : '' }}>Practical</option>
                            <option value="Both" {{ $trainer->training_type == 'Both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>

                    <div class="form-group car-fields">
                        <label for="category">Car Category:</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">Select a category</option>
                            @foreach($carCategories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $trainer->category ? 'selected' : '' }}>
                                    {{ $category->car_category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group car-fields">
                        <label for="car_name">Vehicle:</label>
                        <input type="text" class="form-control" id="car_name" name="car_name" value="{{ $trainer->car_name }}">
                    </div>

                    <div class="form-group car-fields">
                        <label for="plate_no">Plate No:</label>
                        <input type="text" class="form-control" id="plate_no" name="plate_no" value="{{ $trainer->plate_no }}">
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainers.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trainingType = document.getElementById('training_type');
        const carFields = document.querySelectorAll('.car-fields');

        // Function to show or hide car fields
        function toggleCarFields() {
            if (trainingType.value === 'Theoretical') {
                carFields.forEach(field => field.style.display = 'none');
            } else {
                carFields.forEach(field => field.style.display = 'block');
            }
        }

        // Initially check on page load
        toggleCarFields();

        // Add event listener for changes in training type
        trainingType.addEventListener('change', toggleCarFields);
    });
</script>
@endsection