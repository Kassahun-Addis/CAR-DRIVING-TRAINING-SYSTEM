<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CAR DRIVING TRAINING SYSTEM')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Popper.js (for Bootstrap dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        /* Add your styles here */
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Prevent main content from hiding behind the fixed header */
        .main-content {
            margin-top: 65px; /* Adjust this based on your header height */
            position: relative; /* Ensure main content stays below sidebar */
            z-index: 1; /* Lower z-index to ensure it stays below sidebar */
            margin-left: 250px; /* Keep fixed margin for larger screens */
            margin-right: 40px; /* Keep fixed margin for larger screens */
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 240px; /* Keep fixed margin for larger screens */
            }
            #menu-toggle {
            display: none; /* Hide menu toggle button on large devices */
        }
        }

    </style>
</head>
<body>
    @include('partials.navigation') <!-- Include the navigation here -->
    <div class="main-content">
        @yield('content') <!-- This is where child views will insert their content -->
    </div>
    
</body>
</html>
