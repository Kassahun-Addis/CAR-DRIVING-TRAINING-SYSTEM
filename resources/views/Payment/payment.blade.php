@extends('layouts.app')

@section('title', 'Add Payment')

@section('content')
<div class="container mt-5">
    <h2>Add Payment</h2>

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

    <div class="form-section">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row">
            <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="custom_id">Custom ID</label>
                <input type="text" class="form-control" id="custom_id" name="custom_id" required oninput="fetchTraineeInfo()">
            </div>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required readonly>
            </div>

            <div class="form-group">
                <label for="tin_no">Tax Identification Number (TIN)</label>
                <input type="text" class="form-control" id="tin_no" name="tin_no" readonly>
            </div>

                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" required onchange="toggleBankField()">
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
                        <label for="transaction_no">Transaction Number</label>
                        <input type="text" class="form-control" id="transaction_no" name="transaction_no">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                <div class="form-group">
                        <label for="sub_total">Sub Total</label>
                        <input type="number" class="form-control" id="sub_total" name="sub_total" step="0.01" min="0" required oninput="calculateTotals()">
                    </div>

                    <div class="form-group">
                        <label for="vat">VAT (15%)</label>
                        <input type="number" class="form-control" id="vat" name="vat" step="0.01" min="0" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="amount_paid">Amount Paid</label>
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" min="0" oninput="calculateTotals()">
                    </div>

                    <div class="form-group">
                        <label for="discount">Discount(if any)</label>
                        <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" oninput="calculateTotals()">
                    </div>

                    <div class="form-group">
                        <label for="remaining_balance">Remaining Balance</label>
                        <input type="number" class="form-control" id="remaining_balance" name="remaining_balance" step="0.01" min="0" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <input type="text" id="payment_status" name="payment_status" class="form-control" value=" " readonly>
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
document.querySelector('form').addEventListener('submit', function(event) {
    const amountPaidInput = document.getElementById('amount_paid');
    const discountInput = document.getElementById('discount');

    // Set amount paid to 0 if not filled
    if (!amountPaidInput.value) {
        amountPaidInput.value = 0;
    }

    // Set discount to 0 if not filled
    if (!discountInput.value) {
        discountInput.value = 0;
    }

    // Call calculateTotals to ensure totals are updated before submission
    calculateTotals();
});

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
    // Ensure discount and amount paid are not empty
    const discountInput = document.getElementById('discount');
    const amountPaidInput = document.getElementById('amount_paid');

    if (!discountInput.value) {
        discountInput.value = 0;
    }

    if (!amountPaidInput.value) {
        amountPaidInput.value = 0;
    }

    const originalSubtotal = parseFloat(document.getElementById('sub_total').value) || 0;
    let discount = parseFloat(discountInput.value) || 0;

    // Ensure discount is not greater than the subtotal
    if (discount > originalSubtotal) {
        alert('Discount cannot be greater than the Sub Total.');
        discount = originalSubtotal;  // Set discount to match the subtotal if it's too high
        discountInput.value = discount.toFixed(2);  // Update the discount field
    }

    // Calculate the subtotal after applying the discount
    const subtotalAfterDiscount = originalSubtotal - discount;
    
    // Calculate VAT based on the discounted subtotal
    const vat = subtotalAfterDiscount * 0.15;
    
    // Calculate the total amount
    const total = subtotalAfterDiscount + vat;
    
    // Get the amount paid
    const amountPaid = parseFloat(amountPaidInput.value) || 0;
    
    // Calculate the remaining balance
    let remainingBalance = total - amountPaid;

    // Ensure remaining balance is not less than zero
    if (remainingBalance < 0) {
        alert('Remaining balance cannot be less than zero.');
        amountPaidInput.value = total.toFixed(2);  // Set amount paid to match total
        remainingBalance = 0;  // Set remaining balance to 0
    }

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
        paymentStatusSelect.value = 'Partially';
    } else {
        paymentStatusSelect.value = 'Unpaid';
    }
}

function fetchTraineeInfo() {
    const custom_id = document.getElementById('custom_id').value;

    if (custom_id) {
        fetch(`/trainee-info?custom_id=${custom_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.trainee) {
                    document.getElementById('full_name').value = data.trainee.full_name || '';
                    document.getElementById('tin_no').value = data.trainee.tin_no || '';
                } else {
                    document.getElementById('full_name').value = '';
                    document.getElementById('tin_no').value = '';
                }

                if (data.carCategory) {
                    document.getElementById('sub_total').value = data.carCategory.price || 0;
                } else {
                    document.getElementById('sub_total').value = 0;
                }

                calculateTotals(); // Recalculate totals with the new subtotal
            })
            .catch(error => console.error('Error fetching trainee info:', error));
    } else {
        document.getElementById('full_name').value = '';
        document.getElementById('tin_no').value = '';
        document.getElementById('sub_total').value = '';
    }
}

// Success alert timeout
document.addEventListener('DOMContentLoaded', function() {
    var successAlert = document.getElementById('success-alert');

    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 3000);
    }
});
</script>

@endsection