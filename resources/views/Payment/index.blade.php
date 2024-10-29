@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container mt-5">

<h2 style="text-align: center; padding:10px;">Payments List</h2>


    <div class="d-flex justify-content-between align-items-center mb-3"></div>
    
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('payments.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group">
                <span>Show
                    <select name="perPage" class="form-control" onchange="this.form.submit()" style="display: inline-block; width: auto;">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    entries
                </span>
            </div>
        </form>

        <!-- Add New Button -->
        <a href="{{ route('payments.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

            <!-- Search and Export buttons -->
            <div class="col-12 col-md-6 d-flex">
            <form action="{{ route('payments.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                </div>
            </form>

            <div class="d-flex">
                <form action="{{ route('payments.exportExcel') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('payments.exportPdf') }}'">PDF</button>
                    <button type="submit" class="btn btn-primary">Excel</button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Full Name</th>
                <th>TIN No</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Bank Name</th>
                <th>Transaction No</th>
                <th>Sub Total</th>
                <th>VAT</th>
                <th>Total</th>
                <th>Paid Amount</th>
                <th>Remaining Balance</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
           <tbody>
              @foreach($payments as $key => $payment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $payment->full_name }}</td>
                    <td>{{ $payment->tin_no }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->bank ? $payment->bank->bank_name : '' }}</td>
                    <td>{{ $payment->transaction_no }}</td>
                    <td>{{ $payment->sub_total }}</td>
                    <td>{{ $payment->vat }}</td>
                    <td>{{ $payment->total }}</td>
                    <td>{{ $payment->amount_paid }}</td>
                    <td>{{ $payment->remaining_balance }}</td>
                    <td>{{ $payment->payment_status }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning" style="margin-right: 5px;">Edit</a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display:inline; margin-right: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('payments.print', $payment) }}" class="btn btn-secondary" style="margin-right: 5px;">Print</a>
                        
                        <!-- Pay Remaining Dropdown -->
                        <div class="btn-group" style="display: inline-block;">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 8px 12px; border-radius: 4px;">
                                Pay Remaining
                            </button>
                            <div class="dropdown-menu" style="background-color: #ffffff; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);">
                                <a class="dropdown-item" href="{{ route('payments.pay_remaining', $payment) }}" style="color: #007bff; padding: 8px 16px; text-decoration: none; display: block;">
                                    Pay
                                </a>
                                <a class="dropdown-item" href="{{ route('payments.history', $payment) }}" style="color: #007bff; padding: 8px 16px; text-decoration: none; display: block;">
                                    See History
                                </a>
                            </div>
                        </div>
                    </td>

    </tr>
    @endforeach
    </tbody>
    </table>
    </div>


<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($payments->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $payments->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $payments->lastPage()) as $i)
            @if ($i == $payments->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $payments->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($payments->hasMorePages())
            <a href="{{ $payments->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $payments->links() }}
    </div>
</div>


<script>
   // Fetch car details when the trainer is selected
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