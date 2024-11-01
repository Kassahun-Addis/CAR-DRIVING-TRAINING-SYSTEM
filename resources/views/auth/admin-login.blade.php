<!-- resources/views/auth/admin-login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    
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
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            font-weight: 500;
            text-align: center;
            padding: 15px;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }
        .form-control {
            height: 45px;
            font-size: 0.95rem;
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
        .forgot-password-link {
            font-size: 0.85rem;
            text-align: right;
            display: block;
            margin-top: 10px;
        }
        .show-password {
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header">{{ __('Admin Login') }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="email" class="col-form-label text-md-right">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group row mt-3">
                        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="show-password">
                            <input type="checkbox" id="showPasswordCheckbox"> Show Password
                        </div>
                    </div>

                    <div class="row mb-4">
                        <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot Password?</a>
                    </div>

                    <div class="form-group row mb-0">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showPasswordCheckbox').addEventListener('change', function() {
            var passwordField = document.getElementById('password');
            if (this.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    </script>
</body>
</html>