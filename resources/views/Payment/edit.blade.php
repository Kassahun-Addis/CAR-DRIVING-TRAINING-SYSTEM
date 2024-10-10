@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="container mt-5">
    <h2>Edit Payment</h2>
    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="FullName">Full Name</label>
            <input type="text" class="form-control" id="FullName" name="FullName" value="{{ $payment->FullName }}" required>
        </div>
        
        <div class="form-group">
            <label for="TinNo">Tax Identification Number (TIN)</label>
            <input type="text" class="form-control" id="TinNo" name="TinNo" value="{{ $payment->TinNo }}" required>
        </div>

        <div class="form-group">
            <label for="PaymentDate">Payment Date</label>
            <input type="date" class="form-control" id="PaymentDate" name="PaymentDate" value="{{ $payment->PaymentDate }}" required>
        </div>

        <div class="form-group">
            <label for="PaymentMethod">Payment Method</label>
            <select name="PaymentMethod" id="PaymentMethod" class="form-control" required>
                <option value="Cash" {{ $payment->PaymentMethod == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Bank" {{ $payment->PaymentMethod == 'Bank' ? 'selected' : '' }}>Bank</option>
                <option value="Telebirr" {{ $payment->PaymentMethod == 'Telebirr' ? 'selected' : '' }}>Telebirr</option>
            </select>
        </div>

        <div class="form-group">
            <label for="BankName">Bank Name</label>
            <input type="text" class="form-control" id="BankName" name="BankName" value="{{ $payment->BankName }}">
        </div>

        <div class="form-group">
            <label for="TransactionNo">Transaction Number</label>
            <input type="text" class="form-control" id="TransactionNo" name="TransactionNo" value="{{ $payment->TransactionNo }}">
        </div>

        <div class="form-group">
            <label for="SubTotal">Sub Total</label>
            <input type="number" class="form-control" id="SubTotal" name="SubTotal" step="0.01" min="0" value="{{ $payment->SubTotal }}" required>
        </div>

        <div class="form-group">
            <label for="Vat">VAT</label>
            <input type="number" class="form-control" id="Vat" name="Vat" step="0.01" min="0" value="{{ $payment->Vat }}" required>
        </div>

        <div class="form-group">
            <label for="Total">Total</label>
            <input type="number" class="form-control" id="Total" name="Total" step="0.01" min="0" value="{{ $payment->Total }}" required>
        </div>

        <div class="form-group">
            <label for="PaymentStatus">Payment Status</label>
            <select name="PaymentStatus" id="PaymentStatus" class="form-control" required>
                <option value="Paid" {{ $payment->PaymentStatus == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Pending" {{ $payment->PaymentStatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Overdue" {{ $payment->PaymentStatus == 'Overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection