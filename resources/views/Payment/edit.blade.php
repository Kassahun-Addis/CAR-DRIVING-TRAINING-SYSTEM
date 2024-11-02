@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="container mt-5">
    <h2>Edit Payment</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="custom_id">Custom ID</label>
                    <input type="text" class="form-control" id="custom_id" name="custom_id" value="{{ old('custom_id', $payment->custom_id) }}" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $payment->full_name) }}" required readonly>
                </div>
                
                <div class="form-group">
                    <label for="tin_no">Tax Identification Number (TIN)</label>
                    <input type="text" class="form-control" id="tin_no" name="tin_no" value="{{ old('tin_no', $payment->tin_no) }}" readonly>
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

                <div class="form-group" id="bankField" style="{{ old('payment_method', $payment->payment_method) == 'Bank' ? '' : 'display: none;' }}">
                    <label for="bank_id">Bank Name</label>
                    <select name="bank_id" id="bank_id" class="form-control">
                        <option value="" disabled {{ old('bank_id', $payment->bank_id) ? '' : 'selected' }}>Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ $bank->id == old('bank_id', $payment->bank_id) ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="transaction_no">Transaction Number</label>
                    <input type="text" class="form-control" id="transaction_no" name="transaction_no" value="{{ old('transaction_no', $payment->transaction_no) }}">
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="sub_total">Sub Total</label>
                    <input type="number" class="form-control" id="sub_total" name="sub_total" step="0.01" min="0" value="{{ old('sub_total', $payment->sub_total) }}" required oninput="calculateTotals()" readonly>
                </div>

                <div class="form-group">
                    <label for="vat">VAT (15%)</label>
                    <input type="number" class="form-control" id="vat" name="vat" step="0.01" min="0" value="{{ old('vat', $payment->vat) }}" required readonly>
                </div>

                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" value="{{ old('total', $payment->total) }}" required readonly>
                </div>

                <div class="form-group">
                    <label for="amount_paid">Amount Paid</label>
                    <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" min="0" value="{{ old('amount_paid', $payment->amount_paid) }}" required oninput="calculateTotals()">
                </div>

                <div class="form-group">
                    <label for="discount">Discount(if any)</label>
                    <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" value="{{ old('discount', $payment->discount) }}" oninput="calculateTotals()">
                </div>

                <div class="form-group">
                    <label for="remaining_balance">Remaining Balance</label>
                    <input type="number" class="form-control" id="remaining_balance" name="remaining_balance" step="0.01" min="0" value="{{ old('remaining_balance', $payment->remaining_balance) }}" required readonly>
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status</label>
                    <input type="text" id="payment_status" name="payment_status" class="form-control" value="{{ old('payment_status', $payment->payment_status) }}" readonly>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-custom">Update</button>
            <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>
    </form>
</div>

<script>
    function toggleBankField() {
        const paymentMethod = document.getElementById('payment_method').value;
        const bankField = document.getElementById('bankField');

        if (paymentMethod === 'Bank') {
            bankField.style.display = 'block';
            document.getElementById('bank_id').setAttribute('required', 'required');
        } else {
            bankField.style.display = 'none';
            document.getElementById('bank_id').removeAttribute('required');
            document.getElementById('bank_id').value = ''; // Clear the value
        }
    }

    function calculateTotals() {
        const originalSubtotal = parseFloat(document.getElementById('sub_total').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;

        // Calculate the subtotal after applying the discount
        const subtotalAfterDiscount = originalSubtotal - discount;

        // Calculate VAT based on the discounted subtotal
        const vat = subtotalAfterDiscount * 0.15;

        // Calculate the total amount
        const total = subtotalAfterDiscount + vat;

        // Get the amount paid
        const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;

        // Calculate the remaining balance
        const remainingBalance = total - amountPaid;

        // Update the form fields with the calculated values
        document.getElementById('vat').value = vat.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
        document.getElementById('remaining_balance').value = remainingBalance.toFixed(2);

        // Update Payment Status based on the amount paid
        updatePaymentStatus(amountPaid, total);
    }

    function updatePaymentStatus(amountPaid, total) {
        const paymentStatusSelect = document.getElementById('payment_status');
        
        if (amountPaid >= total) {
            paymentStatusSelect.value = 'Paid';
        } else if (amountPaid > 0) {
            paymentStatusSelect.value = 'Partial'; // Corrected to 'Partial'
        } else {
            paymentStatusSelect.value = 'Unpaid';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleBankField();
        calculateTotals(); // Ensure totals and status are calculated on page load
    });
</script>
@endsection