@extends('student.app')
<style>
    @media print {
        .container {
            width: 100%; /* Ensure the container uses full width */
        }

        .row {
            display: flex; /* Use flexbox for alignment */
            flex-direction: row; /* Ensure items align horizontally */
        }

        .col-md-6 {
            flex: 0 0 50%; /* Each column takes 50% of the width */
            max-width: 50%; /* Prevent columns from exceeding 50% */
        }

        /* Hide unnecessary elements during printing */
        .btn {
            display: none; /* Hide buttons on print */
        }
    }
</style>
@section('title', 'Payment Receipt')

@section('content')
<div class="container mt-5">
    <!-- Back and Print Buttons -->
    <div class="row mb-4"> 
        <div class="col-md-12 text-right"style="margin-top: 30px;">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            <button class="btn btn-primary" onclick="window.print()">Print</button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <img src="{{ asset('storage/trainee_photos/12.jfif') }}" alt="Company Logo" height="100" class="mb-2">
            <h5>Solomon Car Driving</h5>
            <p>TIN No: 0006341234</p>
            <p>Phone: +251911234789</p>
            <p>Email: info@solomondriving.com</p>
            <p>Address: Arada, Addis Ababa, Ethiopia</p>
        </div>
        <div class="col-md-6 text-right" style="margin-top: 90px;">
            <p><strong>Invoice No:</strong> {{ $payment->payment_id }}</p>
            <p><strong>Customer TIN:</strong> {{ $payment->tin_no }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
        </div>
    </div>

    <hr>

    <!-- Total Summary Section -->
    <h4 style= "text-align:center;">Invoice Summary</h4>
    <table class="table table-bordered" style="border: 1px solid black;">
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Sub Total:</strong></td>
                <td style="text-align: right;">{{ number_format($payment->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>VAT (15%):</strong></td>
                <td style="text-align: right;">{{ number_format($payment->vat, 2) }}</td>
            </tr>
            <!-- <tr>
    <td colspan="4" style="text-align: right;"><strong>Withhold (2%):</strong></td>
    <td style="text-align: right;">
        {{ isset($trainee) && $trainee->withhold !== null ? number_format($trainee->withhold, 2) : 'N/A' }}
    </td>
</tr> -->
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td style="text-align: right;"><h5>{{ number_format($payment->total, 2) }}</h5></td>
            </tr>
        </tfoot>
    </table>

    <!-- Additional Payment Information -->
    <div class="row mt-3">
        <div class="col-md-6">
            <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
            <!-- <p><strong>Bank Name:</strong> {{ $payment->BankName }}</p> -->
            <p><strong>Transaction No:</strong> {{ $payment->transaction_no }}</p>
        </div>
    </div>
</div>
@endsection