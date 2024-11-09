<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CAR DRIVING TRAINING SYSTEM')</title>



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
<header class="header bg-blue-600 text-white p-2 flex justify-between items-center shadow-lg">
@if($company && $company->logo)
        <img src="{{ asset('storage/company_logos/' . $company->logo) }}" alt="Logo" class="logo-small">
    @else
        <img src="{{ asset('default-logo.png') }}" alt="Default Logo" class="logo-small"> <!-- Fallback logo -->
    @endif
    <h4 class="text-2xl font-bold d-none d-sm-block" style="margin-left: 15px;">CAR Driver Training System</h4>
    <!-- Only show user icon if the user is an admin -->
    @if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'clerk'))
    <div class="flex items-center ml-auto relative"> <!-- Use ml-auto to push this div to the right -->
        <a href="#" class="text-white flex items-center mr-4" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user mr-1"></i>
            <span>{{ Auth::user()->name }}</span> <!-- Display logged-in user's name -->
        </a>
        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white text-black rounded shadow-lg hidden" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="{{ route('account.manage') }}">Manage Account</a>
            <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    @endif
    <div>
        <button id="menu-toggle" class="text-white focus:outline-none hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
</header>
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
            <!-- <li><a href="/admin/login" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li> -->
            <li><a href="/home" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
        </ul>
    @endif
</div>

<div class="main-content">
<!-- <div class = "dashboared">
<h2 style = "background: red">Here is sample pie chart, graph....</h2>
</div> -->
</div>

    <!-- Overlay for mobile menu -->
<div id="overlay" class="overlay"></div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
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

    // Close sidebar when clicking on overlay
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active'); // Hide sidebar
            overlay.style.display = 'none'; // Hide overlay
        });
    } else {
        console.error('overlay is not found in the DOM.');
    }
});
</script>

</body>
</html>


