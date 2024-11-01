<!-- resources/views/auth/trainee-login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trainee Login</title>

    <!-- Bootstrap and Custom CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f9fafb;
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
            background-color: #28a745;
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
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .show-yellow-card {
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header">{{ __('Trainee Login') }}</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('trainee.login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="yellow_card" class="col-form-label text-md-right">{{ __('Yellow Card Number') }}</label>
                        <input id="yellow_card" type="password" class="form-control @error('yellow_card') is-invalid @enderror" name="yellow_card" required>
                        @error('yellow_card')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="show-yellow-card">
                            <input type="checkbox" id="showYellowCardCheckbox"> Show Yellow Card Number
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showYellowCardCheckbox').addEventListener('change', function() {
            var yellowCardField = document.getElementById('yellow_card');
            if (this.checked) {
                yellowCardField.type = 'text';
            } else {
                yellowCardField.type = 'password';
            }
        });
    </script>
</body>
</html>