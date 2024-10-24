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

/* Header font size for small devices */
@media (max-width: 768px) {
        .header h2 {
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
    }
</style>
</head>
<body>
<!-- Header Section -->
<header class="header bg-blue-600 text-white p-2 flex justify-between items-center shadow-lg">
    <h2 class="text-2xl font-bold">CAR Driving Training System</h2>
    <!-- ... existing code ... -->
    <div class="flex items-center ml-auto relative"> <!-- Use ml-auto to push this div to the right -->
        <a href="#" class="text-white flex items-center mr-4" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user mr-1"></i>
            @if (Auth::check())
                <span>{{ Auth::user()->name }}</span> <!-- Display logged-in user's name -->
            @else
                <span>Guest</span> <!-- Fallback if no user is logged in -->
            @endif
        </a>
        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white text-black rounded shadow-lg hidden" aria-labelledby="userDropdown">
            @if (Auth::check())
                <a class="dropdown-item" href="{{ route('account.manage') }}">Manage Account</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </div>
    <div>
        <button id="menu-toggle" class="text-white focus:outline-none hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
</header>
  <!-- Sidebar Section -->
  <div id="sidebar" class="sidebar bg-gray-800 text-white w-64 h-screen fixed z-10 shadow-lg">
    @if (Auth::check() && Auth::user()->role === 'admin') <!-- Check if user is an admin -->
    <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/welcome" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
            <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-user-graduate mr-2"></i>Trainee</a></li>
            <li><a href="{{ route('trainers.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-chalkboard-teacher mr-2"></i>Trainer</a></li>
            <li><a href="{{ route('payments.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-money-check-alt mr-2"></i>Payment</a></li>
            <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-clipboard-check mr-2"></i>Exam</a></li>
            <li><a href="{{ route('training_cars.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-car mr-2"></i>Vehicle</a></li>
            <li><a href="{{ route('banks.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-university mr-2"></i>Bank</a></li>
            <li><a href="{{ route('car_category.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Car Category</a></li>
            <li><a href="{{ route('classes.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-tags mr-2"></i>Class Lists</a></li>
            <li><a href="{{ route('trainer_assigning.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Practical Training </a></li>
            <li><a href="{{ route('theoretical_class.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Theoretical Training </a></li>
            <!-- <li>
            <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-700 rounded" id="order-toggle">
                <span><i class="fas fa-folder-open mr-2"></i>Trainer Assigning</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <ul class="ml-0 hidden" id="order-submenu">
            <li><a href="{{ route('trainer_assigning.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Practical Training </a></li>
            <li><a href="{{ route('theoretical_class.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-users-cog mr-2"></i>Theoretical Training </a></li>
            </ul>
            </li> -->
    </ul>
    @else
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/home" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
           
        </ul>
    @endif
</div>

<div class="main-content">
<div class = "dashboared">
<h2 style = "background: red">Here is sample pie chart, graph....</h2>
</div>
</div>

    <!-- Overlay for mobile menu -->
<div id="overlay" class="overlay"></div>



<script>
    console.log("The err exsists bellow these");
    document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    // Toggle the sidebar for mobile devices
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active'); // Show/hide sidebar
        overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none'; // Show/hide overlay
    });

    // Close sidebar when clicking on overlay
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active'); // Hide sidebar
        overlay.style.display = 'none'; // Hide overlay
    });
});
</script>

</body>
</html>


