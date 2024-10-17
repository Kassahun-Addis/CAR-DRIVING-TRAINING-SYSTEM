<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trainee Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Trainee Dashboard</h2>
        <p>Welcome, {{ $trainee->full_name }}!</p> <!-- Display the trainee's name -->
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">Fill Attendance</a>
        <!-- Add other dashboard features here -->
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>