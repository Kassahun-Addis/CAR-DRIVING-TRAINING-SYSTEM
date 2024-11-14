@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Training Car</h1>

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
        <form action="{{ route('training_cars.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">

                    <div class="form-group">
                        <label for="name">Car Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Car Category:</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category) <!-- Updated variable to match controller -->
                                <!-- <option value="{{ $category->id }}">{{ $category->car_category_name }}</option> Assuming you want to use category name -->
                                <option value="{{ $category->car_category_name }}">{{ $category->car_category_name }}</option> <!-- Assuming you want to use category name -->
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="model">Model:</label>
                        <input type="text" class="form-control" id="model" name="model">
                    </div>

                    <div class="form-group">
                        <label for="year">Year:</label>
                        <input type="number" class="form-control" id="year" name="year" min="1950" max="{{ date('Y') }}">
                    </div>

                    <div class="form-group">
                        <label for="plate_no">Plate No:</label>
                        <input type="text" class="form-control" id="plate_no" name="plate_no" required>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
                <a href="{{ route('training_cars.index') }}" class="btn btn-secondary">Back to list</a>
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
</script>
@endsection