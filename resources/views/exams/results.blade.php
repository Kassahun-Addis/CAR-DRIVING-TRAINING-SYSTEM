@extends('student.app')

@section('content')
<div class="container">
    <h2>Your Exam Results</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Trainee Name</th>
                <th>Score</th>
                <th>Pass/Fail</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exams as $exam)
                <tr>
                    <td>{{ $exam->trainee->full_name }}</td> <!-- Display trainee's full name -->
                    <td>{{ $exam->score }}</td>
                    <td>{{ $exam->score >= 74 ? 'Pass' : 'Fail' }}</td> <!-- Example pass/fail logic -->
                    <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection