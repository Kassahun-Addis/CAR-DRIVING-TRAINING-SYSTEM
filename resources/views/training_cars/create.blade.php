@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Add New Training Car</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-section">
    <form action="{{ route('training_cars.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Car Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="model">Car Model:</label>
            <input type="text" class="form-control" id="model" name="model">
        </div>

        <div class="form-group">
            <label for="year">Year:</label>
            <input type="number" class="form-control" id="year" name="year" min="1900" max="{{ date('Y') }}">
        </div>

        <div class="form-group">
            <label for="plate_no">Plate No:</label>
            <input type="text" class="form-control" id="plate_no" name="plate_no" required>
        </div>

        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('training_cars.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>
        </form>
    </div>
</div>
@endsection