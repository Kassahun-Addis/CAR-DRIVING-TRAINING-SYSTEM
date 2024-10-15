<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .container {
            margin-top: 100px; /* Space above the form */
        }
        .card {
            border: 1px solid #007bff; /* Blue border for the card */
            border-radius: 0.5rem; /* Rounded corners */
        }
        .card-header {
            background-color: #007bff; /* Blue background for header */
            color: white; /* White text for header */
        }
        .btn-primary {
            background-color: #007bff; /* Custom button color */
            border: none; /* Remove border */
        }
        .form-check-label {
            margin-bottom: 0; /* Adjust label spacing */
        }
    </style>
    <script>
    function toggleEmailField() {
        const userType = document.querySelector('input[name="user_type"]:checked').value;
        const emailField = document.getElementById('emailField');
        const emailInput = document.getElementById('email');
        
        if (userType === 'student') {
            emailField.style.display = 'none';
            emailInput.removeAttribute('name');  // Remove 'name' attribute for student
        } else {
            emailField.style.display = 'flex';
            emailInput.setAttribute('name', 'email');  // Restore 'name' attribute for admin
        }
    }
</script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label>{{ __('Login As:') }}</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="admin" id="admin" onclick="toggleEmailField()" checked>
                                    <label class="form-check-label" for="admin">{{ __('Admin') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" value="student" id="student" onclick="toggleEmailField()">
                                    <label class="form-check-label" for="student">{{ __('Student') }}</label>
                                </div>
                            </div>

                            <div class="row mb-3" id="emailField">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Show Password Checkbox -->
                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" style="border-radius: 0.5rem;">
                                        {{ __('Login') }}
                                    </button>

                                    <!-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
