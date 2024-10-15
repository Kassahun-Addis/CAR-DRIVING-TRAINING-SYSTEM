<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>Manage Account</title>
       <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Add this line -->
    <style>
        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Your Account</h2>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('account.update') }}">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
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

            <div class="mb-3">
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

            <button type="submit" class="btn btn-primary">Update Account</button>
            <a href="{{ route('account.manage') }}" class="btn btn-secondary">Cancel</a> <!-- Cancel Button -->
            <a href="http://127.0.0.1:8000/welcome" class="btn btn-info">Dashboard</a> <!-- Dashboard Button -->
        </form>
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