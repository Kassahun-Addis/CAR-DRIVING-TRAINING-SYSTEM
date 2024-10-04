@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Training Cars</h1>

    <a href="{{ route('training_cars.create') }}" class="btn btn-primary">Add New Training Car</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Model</th>
                <th>Year</th>
                <th>Plate No</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainingCars as $car)
                <tr>
                    <td>{{ $car->name }}</td>
                    <td>{{ $car->model }}</td>
                    <td>{{ $car->year }}</td>
                    <td>{{ $car->plate_no }}</td>
                    <td>
                        <a href="{{ route('training_cars.edit', $car->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('training_cars.destroy', $car->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection