<!-- resources/views/layouts/guest.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Guest Page')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Roboto', sans-serif;
        }
        .is-invalid {
            border-color: #dc3545 !important; /* Ensure the red border is applied */
        }
        .invalid-feedback {
            display: block; /* Ensure the feedback message is visible */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>
</body>
</html>