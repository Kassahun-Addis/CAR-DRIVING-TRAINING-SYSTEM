@extends('student.app')

@section('content')

<style>
    .container {
        display: flex;                     /* Enable flexbox layout */
        flex-direction: column;            /* Stack items vertically */
        align-items: center;               /* Center items horizontally */
        padding: 0;                        /* Remove padding */
        min-height: 950px;                 /* Minimum height of the container */
        min-width: 350px !important;       /* Force the minimum width to be applied */
        max-width: 100%;                   /* Ensure the container does not exceed the viewport */
        height: 100vh;                     /* Set height to full viewport height */
    }

    .image-container {
        display: flex;                     /* Use flex for image layout */
        justify-content: center;           /* Center the image */
        margin-bottom: 0;                  /* Remove space below the image */
        width: 100%;                       /* Make the image container take full width */
        flex: 1;                           /* Allow the image container to grow and fill available space */
        overflow: hidden;                  /* Hide overflow from image */
    }

    .image-container img {
        max-width: 100%;                   /* Make sure the image is responsive */
        width: 100%;                       /* Fill the width of the container */
        height: auto;                      /* Maintain aspect ratio */
        object-fit: cover;                 /* Ensure the image covers the area */
        border-radius: 10px;               /* Optional: add rounded corners to the image */
    }

    /* Control the card width and height */
    .card {
        background: rgba(255, 255, 255, 0.9); /* Add a background color with transparency for better readability */
        border: none;                      /* Optional: remove card border */
        border-radius: 10px;              /* Optional: add rounded corners */
        width: 100%;                       /* Set card to take full width */
        max-width: 100%;                  /* Limit the card width */
        margin-top: 20px;                 /* Add space above the card */
        flex: 0 0 auto;                   /* Prevent card from stretching */
        z-index: 1;                       /* Ensure the card is above other elements */
        position: relative;                /* Allow z-index to work */
    }

    /* Media query for smaller devices */
    @media (max-width: 768px) {
        .container {
            min-height: 600px;             /* Adjust the height for smaller screens */
        }

        .card {
            margin-top: 20px;              /* Space between container and card */
            margin-bottom: 10px;           /* Add a 10px gap below the card */
        }
    }

    @media (max-width: 576px) {
        .container {
            min-height: 500px;             /* Further adjust the height for very small screens */
        }

        .card {
            margin-top: 20px;              /* Ensure space above the card */
            margin-bottom: 10px;           /* Maintain 10px gap below the card */
        }
    }
</style>

<div class="container">
    <div class="row justify-content-left" style="width: 100%; padding: 0;">
        <div class="col-md-12" style="width: 100%;">
            <div class="card"> <!-- Card is now above the image -->
                <div class="card-header">{{ __('Welcome to Our Car Driving School') }}</div>
                <div class="card-body">
                    <p>Join our car driving training system and learn how to drive like a professional. Enroll today to enjoy discounts!</p>
                    <!-- Additional promotion text -->
                    <p>Enroll now for our advanced driving techniques course and get invaluable experience on the road. Don't miss out on this limited-time offer!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="image-container">
        <img src="{{ asset('storage/trainee_photos/Driving_Car.webp') }}" alt="Driving Car"> <!-- Display the image -->
    </div>
</div>

@endsection