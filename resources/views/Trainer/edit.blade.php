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
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $trainer->name }}" required>
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
            <label for="car_id">Car ID:</label>
            <select class="form-control" id="car_id" name="car_id" required>
                @foreach ($trainingCars as $car)
                    <option value="{{ $car->id }}" {{ $car->id == $trainer->car_id ? 'selected' : '' }}>
                        {{ $car->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="plate_no">Plate No:</label>
            <input type="text" class="form-control" id="plate_no" name="plate_no" value="{{ $trainer->plate_no }}" required>
        </div>

    </div>
    </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-custom">Update</button>
            <a href="{{ route('trainers.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>
    </form>
    </div>

</div>
@endsection
