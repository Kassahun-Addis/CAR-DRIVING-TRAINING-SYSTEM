@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="container mt-5">
    <h2>Edit Payment</h2>
    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $payment->full_name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="tin_no">Tax Identification Number (TIN)</label>
            <input type="text" class="form-control" id="tin_no" name="tin_no" value="{{ old('tin_no', $payment->tin_no) }}" required>
        </div>

        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', $payment->payment_date) }}" required>
        </div>

        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required onchange="toggleBankField()">
                <option value="Cash" {{ old('payment_method', $payment->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Bank" {{ old('payment_method', $payment->payment_method) == 'Bank' ? 'selected' : '' }}>Bank</option>
                <option value="Telebirr" {{ old('payment_method', $payment->payment_method) == 'Telebirr' ? 'selected' : '' }}>Telebirr</option>
            </select>
        </div>

        <div class="form-group" id="bank_field" style="{{ old('payment_method', $payment->payment_method) == 'Bank' ? '' : 'display: none;' }}">
    <label for="bank_id">Bank Name</label>
    <select name="bank_id" id="bank_id" class="form-control">
        <option value="" disabled {{ old('bank_id', $payment->bank_id) ? '' : 'selected' }}>Select Bank</option> <!-- Default option -->
        @foreach($banks as $bank)
            <option value="{{ $bank->id }}" {{ $bank->id == old('bank_id', $payment->bank_id) ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
        @endforeach
    </select>
</div>
        
        <div class="form-group">
            <label for="transaction_no">Transaction Number</label>
            <input type="text" class="form-control" id="transaction_no" name="transaction_no" value="{{ old('transaction_no', $payment->transaction_no) }}">
        </div>

        <div class="form-group">
            <label for="sub_total">Sub Total</label>
            <input type="number" class="form-control" id="sub_total" name="sub_total" step="0.01" min="0" value="{{ old('sub_total', $payment->sub_total) }}" required>
        </div>

        <div class="form-group">
            <label for="amount_paid">Amount Paid</label>
            <input type="text" name="amount_paid" value="{{ old('amount_paid', $payment->amount_paid) }}">
            </div>

        <div class="form-group">
            <label for="vat">VAT</label>
            <input type="number" class="form-control" id="vat" name="vat" step="0.01" min="0" value="{{ old('vat', $payment->vat) }}" required>
        </div>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" value="{{ old('total', $payment->total) }}" required>
        </div>

        <div class="form-group">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control" required>
                <option value="Paid" {{ old('payment_status', $payment->payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Pending" {{ old('payment_status', $payment->payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Overdue" {{ old('payment_status', $payment->payment_status) == 'Overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
// Function to toggle visibility of bank field based on payment method
function toggleBankField() {
    const paymentMethod = document.getElementById('payment_method').value;
    const bankField = document.getElementById('bank_field');
    bankField.style.display = paymentMethod === 'Bank' ? '' : 'none';
}

// Initialize the bank field visibility based on current payment method
document.addEventListener('DOMContentLoaded', function() {
    toggleBankField();
});
</script>

@endsection
