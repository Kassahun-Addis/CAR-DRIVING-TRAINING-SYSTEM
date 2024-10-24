
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pay Remaining Balance</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="form-section">
    <form action="{{ route('payments.pay_remaining_process', $payment) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" class="form-control" value="{{ $payment->full_name }}" readonly>
                </div>

                <div class="form-group">
                    <label for="remaining_balance">Remaining Balance</label>
                    <input type="number" class="form-control" id="remaining_balance" name="remaining_balance" value="{{ $payment->remaining_balance }}" readonly>
                </div>

                <div class="form-group">
                    <label for="amount_paid">Amount to Pay</label>
                    <input type="number" name="amount_paid" class="form-control" placeholder="Enter amount to pay" step='0.01' required>
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" required>
                </div>

                
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank</option>
                        <option value="Telebirr">Telebirr</option>
                    </select>
                </div>

                <div class="form-group" id="bankField" style="display: none;">
                    <label for="bank_id">Bank Name</label>
                    <select name="bank_id" id="bank_id" class="form-control">
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="transaction_no">Transaction No</label>
                    <input type="text" name="transaction_no" class="form-control" placeholder="Enter transaction number" required>
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="Partial">Partial</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
    </form>
</div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodElement = document.getElementById('payment_method');
    const bankField = document.getElementById('bankField');
    const bankIdElement = document.getElementById('bank_id');
    const remainingBalanceElement = document.getElementById('remaining_balance');
    const amountPaidElement = document.querySelector('input[name="amount_paid"]');

    function toggleBankField() {
        if (paymentMethodElement && bankField && bankIdElement) {
            if (paymentMethodElement.value === 'Bank') {
                bankField.style.display = 'block';
                bankIdElement.setAttribute('required', 'required');
            } else {
                bankField.style.display = 'none';
                bankIdElement.removeAttribute('required');
                bankIdElement.value = ''; // Clear the value
            }
        }
    }

    function calculateTotals() {
        if (remainingBalanceElement && amountPaidElement) {
            const initialRemainingBalance = parseFloat(remainingBalanceElement.value) || 0;
            const amountPaid = parseFloat(amountPaidElement.value) || 0;
            const newRemainingBalance = initialRemainingBalance - amountPaid;

            remainingBalanceElement.value = newRemainingBalance.toFixed(2);
        }
    }

    // Initialize the bank field visibility on page load
    toggleBankField();

    // Add event listener for amount paid input
    if (amountPaidElement) {
        amountPaidElement.addEventListener('input', calculateTotals);
    }

    // Add event listener for payment method change
    if (paymentMethodElement) {
        paymentMethodElement.addEventListener('change', toggleBankField);
    }
});
</script>
@endsection