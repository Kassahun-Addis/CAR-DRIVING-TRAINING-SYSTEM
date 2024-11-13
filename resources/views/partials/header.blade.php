<!-- resources/views/partials/header.blade.php -->
<header class="header bg-blue-600 text-white p-2 flex justify-between items-center shadow-lg">
    @if($company && $company->logo)
        <img src="{{ asset('storage/company_logos/' . $company->logo) }}" alt="Logo" class="logo-small">
    @else
        <img src="{{ asset('storage/company_logos/09874253.jfif') }}" alt="Default Logo" class="logo-small"> <!-- Fallback logo -->
    @endif
    <h4 class="text-2xl font-bold d-none d-sm-block" style="margin-left: 15px;">
        {{ $company->name ?? 'CAR Driver Training System' }}
    </h4>
    <div class="flex items-center ml-auto relative">
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
                <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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