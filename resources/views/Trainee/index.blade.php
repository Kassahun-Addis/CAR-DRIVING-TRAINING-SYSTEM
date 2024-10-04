@extends('layouts.app')

@section('title', 'Trainee - List')

@section('content')
<div class="container mt-5">
    <h2>Trainees List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('trainee.create') }}" class="btn btn-primary">Add New Trainee</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Nationality</th>
                <th>City</th>
                <th>Sub City</th>
                <th>Woreda</th>
                <th>House No</th>
                <th>Phone No</th>
                <th>DOB</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainees as $key => $trainee)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainee->full_name }}</td>
                    <td>{{ $trainee->gender }}</td>
                    <td>{{ $trainee->nationality }}</td>
                    <td>{{ $trainee->city }}</td>
                    <td>{{ $trainee->sub_city }}</td>
                    <td>{{ $trainee->woreda }}</td>
                    <td>{{ $trainee->house_no }}</td>
                    <td>{{ $trainee->phone_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($trainee->dob)->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('trainee.edit', $trainee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('trainee.destroy', $trainee->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainee?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection