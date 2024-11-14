@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Edit Training Car</h1>
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-section">
        <form action="{{ route('training_cars.update', $trainingCar->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Car Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $trainingCar->name }}" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->car_category_name }}" {{ $trainingCar->category == $category->car_category_name ? 'selected' : '' }}>
                            {{ $category->car_category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="model">Car Model:</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ $trainingCar->model }}">
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" class="form-control" id="year" name="year" min="1900" max="{{ date('Y') }}" value="{{ $trainingCar->year }}">
            </div>

            <div class="form-group">
                <label for="plate_no">Plate No:</label>
                <input type="text" class="form-control" id="plate_no" name="plate_no" value="{{ $trainingCar->plate_no }}" required>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <a href="{{ route('training_cars.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>
<script>
    // Success alert timeout
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
    });
@endsection
