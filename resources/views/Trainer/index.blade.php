@extends('layouts.app')

@section('title', 'Trainers - List')

@section('content')
<div class="container mt-5">
    <h2>Trainers List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('trainers.create') }}" class="btn btn-primary">Add New Trainer</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Experience (Years)</th>
                <th>Specialization</th>
                <th>Training Car</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers as $key => $trainer)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer->name }}</td>
                    <td>{{ $trainer->phone_number }}</td>
                    <td>{{ $trainer->email }}</td>
                    <td>{{ $trainer->experience }}</td>
                    <td>{{ $trainer->specialization }}</td>
                    <td>{{ $trainer->trainingCar ? $trainer->trainingCar->name : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('trainers.edit', $trainer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('trainers.destroy', $trainer->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection