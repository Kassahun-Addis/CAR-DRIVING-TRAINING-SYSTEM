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
            <label for="category">Car Category:</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Select a category</option>
                @foreach($carCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->car_category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="car_name">Vehicle:</label>
            <input type="text" class="form-control" id="car_name" name="car_name" required>
        </div>

        <div class="form-group">
            <label for="plate_no">Plate No:</label>
            <input type="text" class="form-control" id="plate_no" name="plate_no" required>
        </div>
 <!-- Hidden field for car_id -->
 <!-- <input type="hidden" id="car_id" name="car_id" value=""> -->

    </div>
</div>

<div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainers.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>
    
    </form>
    </div>

</div>

@endsection