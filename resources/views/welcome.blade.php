<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CAR DRIVING TRAINING SYSTEM')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">                                                                                                                       
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

    <!-- Include Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Include Popper.js (for Bootstrap dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <style>
        /* Add your styles here */
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar {
            width: 230px;
            background-color: #f5f5f5;
            padding: 15px;
            position: fixed;
            height: 100%;
            overflow-y: auto;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #ddd;
        }
     
        
        .form-section {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-primary {
            background-color: #ff9800; /* Orange */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-secondary {
            background-color: #e0e0e0; /* Light gray */
            color: black;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-link {
            background-color: transparent;
            color: black;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-decoration: none;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .container {
            padding-left: 10px;
            padding-right: -10px;
        }


         /* Ensure the header is fixed */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 50;
}


/* Prevent main content from hiding behind the fixed header */
.main-content {
    margin-top: 65px; /* Adjust this based on your header height */
    position: relative; /* Ensure main content stays below sidebar */
    z-index: 1; /* Lower z-index to ensure it stays below sidebar */
    margin-left: 250px; /* Keep fixed margin for larger screens */
    margin-right: 40px; /* Keep fixed margin for larger screens */
}

/* Hide menu toggle button on large devices */
    @media (min-width: 769px) {
        #menu-toggle {
            display: none; /* Hide menu toggle button on large devices */
        }
    }

    /* Overlay for mobile menu */
    #overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none; /* Initially hidden */
        z-index: 90; /* Ensure overlay appears above other content */
    }

    .hidden {
        display: none; /* Ensure hidden elements are not displayed */
    }
    
    /* Sidebar styles */
    #sidebar {
        transform: translateX(0); /* Default visible */
        transition: transform 0.3s ease; /* Smooth transition */
        z-index: 100;

    }

  /* Hide sidebar off-screen on small devices */
  @media (max-width: 768px) {
        #sidebar {
            margin-top:-27px;
            transform: translateX(-100%); /* Hide sidebar off-screen */
        }
        #sidebar.active {
            transform: translateX(0); /* Show sidebar */
        }
        #menu-toggle {
            display: block; /* Show menu toggle button on small devices */
            background:blue;
        }
       
        .main-content {
            margin: 75px 5px 5px 5px;
            padding: 0;
       }

    }

    ul.mt-0 {
    margin-top: 0;
    padding-left: 0;
    list-style-type: none;
}

 .logo-small {
    height: 47px; /* Adjust the height as needed */
    width: auto;  /* Maintain aspect ratio */
}

/* Header font size for small devices */
@media (max-width: 768px) {
        .header h2 {
            font-size: 1.25rem; /* Adjust this value as needed */
        }
        .header h4 {
            font-size: 1.25rem; /* Adjust this value as needed */
        }

        /* Show user info in sidebar */
        .sidebar-user-info {
            display: block;
        }

        /* Hide user info in header */
        .header-user-info {
            display: none;
        }
        .logo-small {
        height: 34px; /* Adjust the height as needed */
        width: auto;  /* Maintain aspect ratio */
    }
    }

   
</style>
</head>
<body>
<!-- Header Section -->
@include('partials.header')
  <!-- Sidebar Section -->
<div id="sidebar" class="sidebar bg-gray-800 text-white w-64 h-screen fixed z-10 shadow-lg">
    @if (Auth::check() && (Auth::user()->role === 'admin'))
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/welcome" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
            <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-user-graduate mr-2"></i>Trainee</a></li>
            <li><a href="{{ route('trainers.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-chalkboard-teacher mr-2"></i>Trainer</a></li>
            <li><a href="{{ route('payments.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-money-check-alt mr-2"></i>Payment</a></li>
            <li><a href="{{ route('exams.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-clipboard-check mr-2"></i>Exam</a></li>
            <li><a href="{{ route('training_cars.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-car mr-2"></i>Vehicle</a></li>
            <li><a href="{{ route('banks.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-university mr-2"></i>Bank</a></li>
            <li><a href="{{ route('car_category.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Training Category</a></li>
            <li><a href="{{ route('classes.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Class Lists</a></li>
            <li><a href="{{ route('trainer_assigning.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Practical Training </a></li>
            <li><a href="{{ route('theoretical_class.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Theoretical Training </a></li>
            <li><a href="{{ route('notifications.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-bell mr-2"></i>Notification</a></li>
            <li><a href="{{ route('companies.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-building mr-2"></i>Company Info</a></li>
            <li><a href="{{ route('reports.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-file-alt mr-2"></i>Reports</a></li>
            <li><a href="{{ route('users.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users mr-2"></i>Users</a></li>
        </ul>
        @elseif (Auth::check() && Auth::user()->role === 'clerk')
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/welcome" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
            <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-user-graduate mr-2"></i>Trainee</a></li>
            <li><a href="{{ route('trainers.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-chalkboard-teacher mr-2"></i>Trainer</a></li>
            <li><a href="{{ route('payments.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-money-check-alt mr-2"></i>Payment</a></li>
            <li><a href="{{ route('exams.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-clipboard-check mr-2"></i>Exam</a></li>
            <li><a href="{{ route('training_cars.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-car mr-2"></i>Vehicle</a></li>
            <li><a href="{{ route('banks.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-university mr-2"></i>Bank</a></li>
            <li><a href="{{ route('car_category.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Training Category</a></li>
            <li><a href="{{ route('classes.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Class Lists</a></li>
            <li><a href="{{ route('trainer_assigning.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Practical Training </a></li>
            <li><a href="{{ route('theoretical_class.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Theoretical Training </a></li>
            <li><a href="{{ route('notifications.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-bell mr-2"></i>Notification</a></li>
            <li><a href="{{ route('companies.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-building mr-2"></i>Company Info</a></li>
            <li><a href="{{ route('reports.index') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-file-alt mr-2"></i>Reports</a></li>
        </ul>
    @else
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/admin/login" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
            <!-- <li><a href="/home" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li> -->
        </ul>
    @endif
</div>

<div class="main-content">
    <div class="container">
    <h3 class="text-left my-4">{{ $company->name ?? 'Dashboard Overview' }}</h3>
        <div class="row text-center">
            <!-- Card 1 -->
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-users" ></i> Total Trainees
                    </div>
                    <div class="card-body">
                        <h5 class="card-title display-4" style="font-size: 1.5rem;">{{ $totalTrainees }}</h5>
                        <a href="{{ route('trainee.index') }}" class="btn btn-outline-light btn-block mt-3">View List</a>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-3">
                <div class="card text-white bg-success shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-chalkboard-teacher"></i> Total Trainers
                    </div>
                    <div class="card-body">
                        <h5 class="card-title display-4" style="font-size: 1.5rem;">{{ $totalTrainers }}</h5>
                        <a href="{{ route('trainers.index') }}" class="btn btn-outline-light btn-block mt-3">View List</a>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-coins"></i> Total Amount Paid
                    </div>
                    <div class="card-body">
                        <h5 class="card-title display-4" style="font-size: 1.5rem;">{{ number_format($totalAmountPaid, 2) }} Birr</h5>
                        <a href="{{ route('payments.index', ['filter' => 'amount_paid']) }}" class="btn btn-outline-light btn-block mt-3">View List</a>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle"></i> Total Remaining Balance
                    </div>
                    <div class="card-body">
                        <h5 class="card-title display-4" style="font-size: 1.5rem;">{{ number_format($totalRemainingBalance, 2) }} Birr</h5>
                        <a href="{{ route('payments.index', ['filter' => 'remaining_balance']) }}" class="btn btn-outline-light btn-block mt-3">View List</a>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="row mt-5">
    <div class="col-md-6">
        <div class="card shadow" style="height: 450px;"> <!-- Set a fixed height on the card -->
            <div class="card-header bg-info text-white">
                <i class="fas fa-chart-bar"></i> Trainees Progress
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="barChart" style="max-height: 400px; width: 100%;"></canvas> <!-- Ensure canvas fills the card -->
            </div>
        </div>
    </div>
        <div class="col-md-6">
        <div class="card shadow" style="height: 450px;">
            <div class="card-header bg-info text-white">
                <i class="fas fa-chart-pie"></i> Trainee Distribution
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="pieChart" style="max-height: 400px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- Overlay for mobile menu -->
<div id="overlay" class="overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    // Toggle the sidebar for mobile devices
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active'); // Show/hide sidebar
            overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none'; // Show/hide overlay
        });
    } else {
        console.error('menuToggle is not found in the DOM.');
    }


        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Total Trainees',
                    data: @json(array_values($monthlyTrainees)),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Disable aspect ratio to allow custom sizing
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json(array_keys($assignmentsByCategory)), // Category IDs as labels
                datasets: [{
                    label: ' Number of Trainee ',
                    data: @json(array_values($assignmentsByCategory)), // Counts as data
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

</body>
</html>


