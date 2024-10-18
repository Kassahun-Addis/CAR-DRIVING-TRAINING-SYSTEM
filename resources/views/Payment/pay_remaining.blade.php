@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pay Remaining Balance</h2>

    <form action="{{ route('payments.pay_remaining_process', $payment) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" value="{{ $payment->full_name }}" readonly>
        </div>

        <div class="form-group">
            <label for="remaining_balance">Remaining Balance</label>
            <input type="text" class="form-control" value="{{ $payment->remaining_balance }}" readonly>
        </div>

        <div class="form-group">
            <label for="amount_paid">Amount to Pay</label>
            <input type="number" name="amount_paid" class="form-control" placeholder="Enter amount to pay" step='0.01' required>
        </div>

        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="transaction_no">Transaction No</label>
            <input type="text" name="transaction_no" class="form-control" placeholder="Enter transaction number" required>
        </div>

        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="Cash">Cash</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Card">Card</option>
            </select>
        </div>

        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
        </div>

        <div class="form-group">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" class="form-control">
                <option value="Partial">Partial</option>
                <option value="Paid">Paid</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
@endsection
