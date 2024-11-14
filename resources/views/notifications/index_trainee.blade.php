@extends('student.app')

@section('title', 'Notifications - List')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Notification List</h1>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="row mb-3">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <form action="{{ route('notifications.index') }}" method="GET" class="form-inline">
                <div class="form-group">
                    <span>Show
                        <select name="perPage" class="form-control" onchange="this.form.submit()" style="display: inline-block; width: auto;">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        entries
                    </span>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-end">
            <form action="{{ Auth::guard('trainee')->check() ? route('trainee.notifications') : route('notifications.index') }}" method="GET" class="form-inline w-100">
            <form action="{{ route('trainee.notifications') }}" method="GET" class="form-inline w-100">    
            <div class="form-group w-100 d-flex justify-content-end align-items-center">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px;">
                    <button type="submit" class="btn btn-primary d-flex align-items-center" style="height: 40px;">Search</button>
                </div>
            </form>
        </div>
    </div>

        <ul class="list-group" id="notification-list">
        @foreach($unreadNotifications as $notification)
            <li class="list-group-item p-4 notification-item" id="notification-{{ $notification->id }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <i class="fas fa-bell text-primary mr-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="mb-1 font-weight-bold">{{ $notification->title }}</h5>
                            <small class="text-muted">{{ $notification->created_at->format('F j, Y, g:i a') }}</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-sm btn-outline-primary mr-2" onclick="toggleDetails({{ $notification->id }})">
                            <span id="toggle-text-{{ $notification->id }}">View Details</span>
                        </button>
                        @if(Auth::guard('trainee')->check())
                            <button class="btn btn-sm btn-outline-secondary mark-as-read-button" onclick="markAsRead({{ $notification->id }})">
                                Mark as Read
                            </button>
                        @endif
                    </div>
                </div>
                <div id="notification-content-{{ $notification->id }}" class="notification-content mt-3">
                    <p>{{ $notification->content }}</p>
                </div>
            </li>
        @endforeach

        @foreach($readNotifications as $notification)
            <li class="list-group-item p-4 notification-item" id="notification-{{ $notification->id }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <i class="fas fa-bell text-primary mr-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="mb-1 font-weight-bold">{{ $notification->title }}</h5>
                            <small class="text-muted">{{ $notification->created_at->format('F j, Y, g:i a') }}</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-sm btn-outline-primary mr-2" onclick="toggleDetails({{ $notification->id }})">
                            <span id="toggle-text-{{ $notification->id }}">View Details</span>
                        </button>
                    </div>
                </div>
                <div id="notification-content-{{ $notification->id }}" class="notification-content mt-3">
                    <p>{{ $notification->content }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<style>
    /* Styles for notification content and overall layout */
    .notification-content {
        display: none;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .show-content {
        display: block;
    }

    .notification-item {
        background-color: #e9ecef;
        border-left: 5px solid #007bff;
        margin-bottom: 1rem;
    }

    .notification-item.read {
        background-color: #f1f1f1;
        border-left: 5px solid #6c757d;
        opacity: 0.8;
        font-style: italic;
    }

    /* Responsive layout adjustments */
    @media (max-width: 576px) {
        .notification-item {
            padding: 1rem;
        }

        .notification-item h5 {
            font-size: 1.1rem;
        }

        .notification-item small {
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .add-new-btn {
            width: 127px;
            height: 40px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var successAlert = document.getElementById('success-alert');

        if (successAlert) {
            setTimeout(function() {
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 500);
            }, 3000); // Alert will fade out after 3 seconds
        }
    });

    function toggleDetails(notificationId) {
    const contentDiv = document.getElementById(`notification-content-${notificationId}`);
    const toggleText = document.getElementById(`toggle-text-${notificationId}`);

    if (contentDiv.classList.contains('show-content')) {
        contentDiv.classList.remove('show-content');
        toggleText.textContent = "View Details"; // Change button text to "View Details"
    } else {
        contentDiv.classList.add('show-content');
        toggleText.textContent = "Hide Details"; // Change button text to "Hide Details"
        markAsRead(notificationId); // Mark notification as read when details are shown
    }
}

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            const notificationItem = document.getElementById(`notification-${notificationId}`);
            notificationItem.classList.add('read'); // Mark notification as read
            // Removed the line that resets the toggle text
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}
</script>
@endsection