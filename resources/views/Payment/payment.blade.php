@extends('layouts.app')

@section('title', 'Add Payment')

@section('content')
<div class="container mt-5">
    <h2>Add Payment</h2>

    <div class="form-section">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tin_no">Tax Identification Number (TIN)</label>
                        <input type="text" class="form-control" id="tin_no" name="tin_no" required>
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

                <div class="col-6">
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
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" min="0" required oninput="calculateTotals()">
                    </div>

                    <div class="form-group">
                        <label for="remaining_balance">Remaining Balance</label>
                        <input type="number" class="form-control" id="remaining_balance" name="remaining_balance" step="0.01" min="0" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Overdue">Overdue</option>
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
    function toggleBankField() {
    const paymentMethod = document.getElementById('payment_method').value;
    const bankField = document.getElementById('bankField');

    if (paymentMethod === 'Bank') {
        bankField.style.display = 'block';
        document.getElementById('bank_id').setAttribute('required', 'required');
    } else {
        bankField.style.display = 'none';
        document.getElementById('bank_id').removeAttribute('required');
        
        // Unset bank_id value if not using Bank
        document.getElementById('bank_id').value = ''; // Clear the value
    }
}

    function calculateTotals() {
        const subtotal = parseFloat(document.getElementById('sub_total').value) || 0;
        const vat = subtotal * 0.15;
        const total = subtotal + vat;
        const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
        const remainingBalance = total - amountPaid;

        document.getElementById('vat').value = vat.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
        document.getElementById('remaining_balance').value = remainingBalance.toFixed(2);
    }
</script>
@endsection
