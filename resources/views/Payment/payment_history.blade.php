@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Payment History for {{ $payment->full_name }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Transaction No</th>
                <th>Payment Method</th>
                <th>Bank Name</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentHistory as $history)
                <tr>
                    <td>{{ $history->amount_paid }}</td>
                    <td>{{ $history->payment_date }}</td>
                    <td>{{ $history->transaction_no }}</td>
                    <td>{{ $history->payment_method }}</td>
                    <td>{{ $history->bank_name }}</td>
                    <td>{{ $history->payment_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
