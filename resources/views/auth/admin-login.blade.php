<!-- resources/views/auth/admin-login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    
    <!-- Bootstrap and Custom CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Roboto', sans-serif;
        }
                .login-container {
            margin-top: 80px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            /* Remove margin: auto; to prevent overriding */
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5rem; /* Increased font size */
            font-weight: 500;
            text-align: center;
            padding: 20px;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }
        .form-control {
            height: 45px;
            font-size: 0.95rem;
            border-radius: 4px; /* Rounded corners */
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 4px;
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .show-password {
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .role-select {
            margin-bottom: 15px;
        }
        .radio-group {
            margin-bottom: 15px;
        }
        .radio-label {
            margin-right: 20px; /* Space between radio buttons */
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header">{{ __('Login') }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <!-- resources/views/auth/admin-login.blade.php -->
                    <div class="form-group role-select">
                        <label>{{ __('Select Role') }}</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="role" value="admin" required> {{ __('Admin') }}
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="role" value="clerk"> {{ __('Clerk') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="show-password">
                            <input type="checkbox" id="showPasswordCheckbox"> <label for="showPasswordCheckbox">Show Password</label>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <p>{{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Sign Up') }}</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showPasswordCheckbox').addEventListener('change', function() {
            var passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>