@extends('layouts.app')

@section('title', 'Add Payment')

@section('content')
<div class="container mt-5">
    <h2>Add Payment</h2>
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="FullName">Full Name</label>
            <input type="text" class="form-control" id="FullName" name="FullName" required>
        </div>
        
        <div class="form-group">
            <label for="TinNo">Tax Identification Number (TIN)</label>
            <input type="text" class="form-control" id="TinNo" name="TinNo" required>
        </div>

        <div class="form-group">
            <label for="PaymentDate">Payment Date</label>
            <input type="date" class="form-control" id="PaymentDate" name="PaymentDate" required>
        </div>

        <div class="form-group">
            <label for="PaymentMethod">Payment Method</label>
            <select name="PaymentMethod" id="PaymentMethod" class="form-control" required onchange="toggleBankField()">
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
                <option value="Telebirr">Telebirr</option>
            </select>
        </div>

        <div class="form-group" id="bankField" style="display: none;">
            <label for="BankID">Bank Name</label>
            <select name="BankID" id="BankID" class="form-control">
                @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="TransactionNo">Transaction Number</label>
            <input type="text" class="form-control" id="TransactionNo" name="TransactionNo">
        </div>

        <div class="form-group">
            <label for="SubTotal">Sub Total</label>
            <input type="number" class="form-control" id="SubTotal" name="SubTotal" step="0.01" min="0" required oninput="calculateTotals()">
        </div>

        <div class="form-group">
            <label for="Vat">VAT (15%)</label>
            <input type="number" class="form-control" id="Vat" name="Vat" step="0.01" min="0" required readonly>
        </div>

        <div class="form-group">
            <label for="Total">Total</label>
            <input type="number" class="form-control" id="Total" name="Total" step="0.01" min="0" required readonly>
        </div>

        <div class="form-group">
            <label for="PaymentStatus">Payment Status</label>
            <select name="PaymentStatus" id="PaymentStatus" class="form-control" required>
                <option value="Paid">Paid</option>
                <option value="Pending">Pending</option>
                <option value="Overdue">Overdue</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<script>
    function toggleBankField() {
        const paymentMethod = document.getElementById('PaymentMethod').value;
        const bankField = document.getElementById('bankField');

        // Show the bank field only if the selected payment method is 'Bank'
        if (paymentMethod === 'Bank') {
            bankField.style.display = 'block';
            // Make the BankID field required
            document.getElementById('BankID').setAttribute('required', 'required');
        } else {
            bankField.style.display = 'none';
            // Remove the required attribute from the BankID field
            document.getElementById('BankID').removeAttribute('required');
        }
    }

    function calculateTotals() {
        const subtotal = parseFloat(document.getElementById('SubTotal').value) || 0;
        const vat = subtotal * 0.15;  // Calculate VAT (15%)
        const total = subtotal + vat;  // Calculate Total

        // Update the VAT and Total fields
        document.getElementById('Vat').value = vat.toFixed(2);
        document.getElementById('Total').value = total.toFixed(2);
    }
</script>
@endsection