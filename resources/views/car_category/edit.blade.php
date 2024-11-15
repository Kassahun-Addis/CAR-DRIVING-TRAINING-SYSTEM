@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Car Category</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="form-section">
    <form action="{{ route('car_category.update', $CarCategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="car_category_name">Car Category Name</label>
            <input type="text" name="car_category_name" class="form-control" value="{{ $CarCategory->car_category_name }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" class="form-control" value="{{ $CarCategory->price }}" required>
        </div>

        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <a href="{{ route('car_category.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
    </form>
  </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('success-alert')) {
            setTimeout(function() {
                document.getElementById('success-alert').style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection