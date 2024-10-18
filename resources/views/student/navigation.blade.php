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
            margin-top:-18px;
            transform: translateX(-100%); /* Hide sidebar off-screen */
        }
        #sidebar.active {
            transform: translateX(0); /* Show sidebar */
        }
        #menu-toggle {
            display: block; /* Show menu toggle button on small devices */
            background: blue;
        }
    }

    /* Hide menu toggle button on large devices */
    @media (min-width: 769px) {
        #menu-toggle {
            display: none; /* Hide menu toggle button on large devices */
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

    /* Header font size for larger devices */
    @media (min-width: 769px) {
        /* Hide user info in sidebar */
        .sidebar-user-info {
            display: none;
        }

        /* Show user info in header */
        .header-user-info {
            display: flex;
        }
    }
</style>

<!-- Header Section -->
<header class="header bg-blue-600 text-white p-2 flex justify-between items-center shadow-lg">
    <h2 class="text-2xl font-bold">CAR Driving Training System</h2>
    <div class="header-user-info flex items-center ml-auto hidden md:flex">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" class="text-white flex items-center mr-4" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            @if (Auth::guard('trainee')->check())
                @php
                    $trainee = Auth::guard('trainee')->user();
                @endphp
                @if ($trainee->photo)
                    <img 
                        src="{{ asset('storage/trainee_photos/' . $trainee->photo) }}" 
                        alt="{{ $trainee->full_name }}" 
                        style="width: 28px; height: 28px; object-fit: cover;" 
                        class="rounded-full mr-1">
                @else
                    <i class="fas fa-user mr-1"></i>
                @endif
                <span>{{ $trainee->full_name }}</span>
            @else
                <i class="fas fa-user mr-1"></i>
                <span>Guest</span>
            @endif
        </a>
        <a href="#" class="text-white btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    </div>
    <div>
        <button id="menu-toggle" class="text-white focus:outline-none hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
</header>

<!-- Sidebar Section -->
<div id="sidebar" class="sidebar bg-gray-800 text-white w-64 h-screen fixed z-10 shadow-lg">
    @if (Auth::guard('trainee')->check())
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <!-- Always show Dashboard -->
            <li><a href="/home" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
            
            <!-- Check if the current route is for filling attendance -->
            @if (!request()->routeIs('attendance.create'))
                <li><a href="{{ route('attendance.create') }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>Fill Attendance</a></li>
            @endif

            <!-- Show View Agreement link only if not in the attendance create route -->
            @auth
                @if (!request()->routeIs('attendance.create'))
                    <li><a href="{{ route('trainee.agreement', ['id' => auth()->user()->id]) }}" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-briefcase mr-2"></i>View Agreement</a></li>
                @endif
            @endauth

            <!-- User Info & Logout (Visible only on small devices) -->
            <li class="sidebar-user-info block md:hidden">
                <a href="#" class="flex items-center p-2 hover:bg-gray-700 rounded" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @if (Auth::guard('trainee')->check())
                        @if ($trainee->photo)
                            <img 
                                src="{{ asset('storage/trainee_photos/' . $trainee->photo) }}" 
                                alt="{{ $trainee->full_name }}" 
                                style="width: 28px; height: 28px; object-fit: cover;" 
                                class="rounded-full mr-1">
                        @else
                            <i class="fas fa-user mr-1"></i>
                        @endif
                        <span>{{ $trainee->full_name }}</span>
                    @else
                        <i class="fas fa-user mr-1"></i>
                        <span>Guest</span>
                    @endif
                </a>
                <a href="#" class="text-white btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </li>
        </ul>
    @else
        <ul class="mt-0 space-y-1 pl-0 list-none">
            <li><a href="/welcome" class="flex items-center p-2 hover:bg-gray-700 rounded"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
        </ul>
    @endif
</div>

<!-- Overlay for mobile menu -->
<div id="overlay" class="overlay"></div>



<script>
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
            categorySubmenu.classList.add('hidden'); // Ensure submenu is hidden
            orderSubmenu.classList.add('hidden'); // Ensure submenu is hidden

        });
    });
</script>
