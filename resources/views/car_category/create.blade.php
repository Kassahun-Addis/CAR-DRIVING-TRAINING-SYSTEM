@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Car Category</h2>

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
    <form action="{{ route('car_category.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="car_category">Car Category Name</label>
            <input type="text" name="car_category_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" class="form-control" required>
        </div>
        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('car_category.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
    </form>
  </div>
</div>
@endsection