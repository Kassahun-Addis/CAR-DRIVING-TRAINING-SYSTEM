@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Register Trainer</h1>

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
    <form action="{{ route('trainers.store') }}" method="POST">
        @csrf
        <div class="row">
        <div class="col-12">

        <div class="form-group">
            <label for="trainer_name">Trainer Name:</label>
            <input type="text" class="form-control" id="trainer_name" name="trainer_name" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="experience">Experience (in years):</label>
            <input type="number" class="form-control" id="experience" name="experience" required>
        </div>

        <div class="form-group">
            <label for="training_type">Training Type</label>
            <select name="training_type" id="training_type" class="form-control" required>
                <option value="Theoretical">Theoretical</option>
                <option value="Practical">Practical</option>
                <option value="Both">Both</option>
            </select>
        </div>

        <div class="form-group car-fields">
            <label for="category">Car Category:</label>
            <select class="form-control" id="category" name="category">
                <option value="">Select a category</option>
                @foreach($carCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->car_category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group car-fields">
    <label for="car_name">Vehicle:</label>
    <select class="form-control" id="car_name" name="car_id">
        <option value="">Select a vehicle</option>
        <!-- Options will be populated dynamically -->
    </select>
</div>

        <div class="form-group car-fields">
            <label for="plate_no">Plate No:</label>
            <input type="text" class="form-control" id="plate_no" name="plate_no" readonly>
        </div>

    </div>
</div>

<div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainers.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>

        
        <input type="hidden" id="car_name_hidden" name="car_name">

    </form>
    </div>

</div>

<!-- Hidden input to store the car name -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trainingType = document.getElementById('training_type');
        const carFields = document.querySelectorAll('.car-fields');
        const categorySelect = document.getElementById('category');
        const carNameSelect = document.getElementById('car_name');
        const carNameHiddenInput = document.getElementById('car_name_hidden');
        const plateNoInput = document.getElementById('plate_no');

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

        // Fetch vehicles when category changes
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                fetch(`/trainers/cars-by-category/${categoryId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        carNameSelect.innerHTML = '<option value="">Select a vehicle</option>';
                        data.forEach(car => {
                            const option = document.createElement('option');
                            option.value = car.id;
                            option.textContent = car.name;
                            carNameSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cars:', error));
            } else {
                carNameSelect.innerHTML = '<option value="">Select a vehicle</option>';
            }
        });

        // Fetch plate number and set car name when a vehicle is selected
        carNameSelect.addEventListener('change', function() {
            const carId = this.value;
            const carName = this.options[this.selectedIndex].text;
            carNameHiddenInput.value = carName; // Set the hidden input with the car name
            console.log('Selected Car Name:', carName); // Debugging: Log the selected car name

            if (carId) {
                fetch(`/trainers/plate-number-by-car/${carId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        plateNoInput.value = data.plate_no || '';
                    })
                    .catch(error => console.error('Error fetching plate number:', error));
            } else {
                plateNoInput.value = '';
            }
        });
    });
</script>

@endsection