@extends('student.app')

@section('content')

<style>
    /* Apply the background image to the container */
    .container {
        background-image: url('{{ asset('storage/trainee_photos/Driving_Car.webp') }}'); /* Path to your background image */
        background-size: cover;        /* Ensures the image covers the entire container */
        background-position: center;   /* Centers the image within the container */
        background-repeat: no-repeat;  /* Prevents the image from repeating */
        padding: 20px;
        border-radius: 10px;           /* Optional: adds rounded corners to the content area */
        min-height: 950px;
        min-width: 350px !important;   /* Force the minimum width to be applied */
        max-width: 100%;               /* Ensure the container does not exceed the viewport */
        position: relative;            /* Allow positioning of child elements */
        overflow: hidden;              /* Hide overflow from child elements */
    }

    /* Ensure the row expands with content */
    .row {
        height: 100%;
    }

    /* Control the column width to make sure content fits well */
    .col-md-8 {
        max-width: 100%;
        position: relative;           /* Allow positioning of the card */
        z-index: 1;                  /* Ensure the card is above the background */
    }

    /* Media query for smaller devices */
    @media (max-width: 768px) {
        .container {
            min-height: 600px;         /* Adjust the height for smaller screens */
            padding: 10px;             /* Reduce padding on smaller screens */
        }
        
        .card {
            margin-top: 20px;         /* Space between container and card */
            margin-bottom: 10px;      /* Add a 10px gap below the card */
            background: rgba(255, 255, 255, 0.9); /* Add a background color with transparency for better readability */
        }
    }

    @media (max-width: 576px) {
        .container {
            min-height: 500px;         /* Further adjust the height for very small screens */
            padding: 5px;              /* Further reduce padding */
        }

        .card {
            margin-top: 20px;         /* Ensure space above the card */
            margin-bottom: 10px;      /* Maintain 10px gap below the card */
        }
    }
</style>

<div class="container">
    <div class="row justify-content-left">
        <!-- Content goes here, you can add text or promotions -->
        <div class="col-md-8">
            <div class="card d-none d-md-block"> <!-- Hide on small devices -->
                <div class="card-header">{{ __('Welcome to Our Car Driving School') }}</div>
                <div class="card-body">
                    <p>Join our car driving training system and learn how to drive like a professional. Enroll today to enjoy discounts!</p>
                    <!-- Additional promotion text -->
                    <p>Enroll now for our advanced driving techniques course and get invaluable experience on the road. Don't miss out on this limited-time offer!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection