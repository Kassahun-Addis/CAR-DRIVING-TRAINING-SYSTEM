<!-- Include Font Awesome for icons -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->


<style>
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
}

/* Sidebar styles */
#sidebar {
    transform: translateX(0); /* Default visible */
    transition: transform 0.3s ease; /* Smooth transition */
    z-index: 100; /* Increase z-index to ensure it overrides other content */
}

/* Overlay for mobile menu */
#overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none; /* Initially hidden */
    z-index: 90; /* Ensure the overlay is between sidebar and main content */
}

.hidden {
    display: none; /* Ensure hidden elements are not displayed */
}

    /* Hide sidebar off-screen on small devices */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%); /* Hide sidebar off-screen */
        }
        #sidebar.active {
            transform: translateX(0); /* Show sidebar */
        }
        #menu-toggle {
            display: block; /* Show menu toggle button on small devices */
            background:blue;
        }
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
        z-index: 40; /* Ensure overlay appears above other content */
    }


 
    .hidden {
        display: none; /* Ensure hidden elements are not displayed */
    }
    
    /* Sidebar styles */
    #sidebar {
        transform: translateX(0); /* Default visible */
        transition: transform 0.3s ease; /* Smooth transition */
    }

    /* Hide sidebar off-screen on small devices */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%); /* Hide sidebar off-screen */
            background: bg-gray-800;
        }
        #sidebar.active {
            transform: translateX(0); /* Show sidebar */
            
        }
    }

    ul.mt-0 {
    margin-top: 0;
    padding-left: 0;
    list-style-type: none;
}

</style>

<!-- Header Section -->
<header class="header bg-blue-600 text-white p-2 flex justify-between items-center shadow-lg">
    <h2 class="text-2xl font-bold">CAR Driving Training System</h2>
    <div class="flex items-center ml-auto"> <!-- Use ml-auto to push this div to the right -->
        <!-- <a href="#" class="text-white relative mr-4">
            <i class="fas fa-bell"></i>
            <span class="absolute top-0 right-0 bg-red-600 text-xs rounded-full px-1">3</span>
        </a> -->
        <!-- <a href="#" class="text-white flex items-center mr-4">
            <i class="fas fa-user mr-1"></i>
            <span>{{ Auth::user()->name }}</span> 
        </a> -->
        <a href="#" class="text-white flex items-center mr-4">
            <i class="fas fa-user mr-1"></i>
            @if (Auth::check())
                <span>{{ Auth::user()->name }}</span> <!-- Display logged-in user's name -->
            @else
                <span>Guest</span> <!-- Fallback if no user is logged in -->
            @endif
        </a>
        <button id="menu-toggle" class="text-white focus:outline-none hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
</header>

<!-- Sidebar Section -->
<div id="sidebar" class="sidebar bg-gray-800 text-white w-64 h-screen fixed z-10 shadow-lg">
    <ul class="mt-0 space-y-1 pl-0 list-none">
        <li><a href="/welcome" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
        <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Trainee</a></li>
        <li><a href="{{ route('trainers.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Trainer</a></li>
        <li><a href="{{ route('payments.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Payment</a></li>
        <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Schedule</a></li>
        <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Exam</a></li>
        <li><a href="{{ route('training_cars.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Vehicle</a></li>
        <!-- <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Theoretical Session</a></li>
        <li><a href="{{ route('trainee.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Practical Session</a></li>
        <li><a href="{{ route('attendance.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Attendance </a></li> -->
        <li><a href="{{ route('banks.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Bank </a></li>
        <li><a href="{{ route('car_category.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Car Category </a></li>
        <li><a href="{{ route('trainer_assigning.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Trainer Assignment </a></li>

    </ul>
</div>

<!-- Overlay for mobile menu -->
<div id="overlay" class="overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const categoryToggle = document.getElementById('category-toggle');
        const categorySubmenu = document.getElementById('category-submenu');
        const orderToggle = document.getElementById('order-toggle');
        const orderSubmenu = document.getElementById('order-submenu');

        // Toggle the sidebar for mobile devices
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active'); // Show/hide sidebar
            overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none'; // Show/hide overlay
        });

        // Toggle the category submenu
        categoryToggle.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor behavior
            categorySubmenu.classList.toggle('hidden'); // Show/hide submenu
        });

        orderToggle.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor behavior
            orderSubmenu.classList.toggle('hidden'); // Show/hide submenu
        });

        // Close sidebar when clicking on overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active'); // Hide sidebar
            overlay.style.display = 'none'; // Hide overlay
            categorySubmenu.classList.add('hidden'); // Ensure submenu is hidden
            orderSubmenu.classList.add('hidden'); // Ensure submenu is hidden

        });
    });
</script>
