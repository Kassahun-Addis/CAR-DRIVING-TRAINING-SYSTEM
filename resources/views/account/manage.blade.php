<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e9ecef; /* Light background for better contrast */
        }
        .card {
            border-radius: 1.5rem; /* Rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .card-title {
            font-size: 1.75rem; /* Larger title font size */
            color: #343a40; /* Dark color for title */
        }
        .form-label {
            font-weight: bold; /* Bold labels */
            color: #495057; /* Darker label color */
        }
        .toggle-password {
            cursor: pointer;
        }
        .btn {
            transition: background-color 0.3s, transform 0.3s; /* Smooth button transition */
        }
        .btn:hover {
            background-color: #0056b3; /* Darker blue on hover for primary button */
            transform: translateY(-2px); /* Slight lift on hover */
        }
        .invalid-feedback {
            display: block; /* Ensure error messages are displayed */
        }
        .custom-container {
            width: 75%;
        }
        @media (min-width: 576px) {
            .btn-group {
                display: flex; /* Flex layout for larger screens */
                justify-content: space-between;
            }
           
        }

        @media (max-width: 576px) {

            .custom-container {
            width: 100%;
        }
        }
    </style>
</head>
<body>
    <div class="container mt-5 custom-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">Manage Your Account</h2>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="fas fa-eye" id="password-confirmation-icon"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group mt-3" style="gap:20px;">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('account.manage') }}" class="btn btn-secondary">Cancel</a>
                        <a href="http://127.0.0.1:8000/welcome" class="btn btn-info">Dashboard</a>
                    </div>                    
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId === 'password' ? 'password-icon' : 'password-confirmation-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>