@extends('layouts.app')

@section('title', 'Bank - List')

@section('content')
<div class="container mt-5">
<h2 style="text-align: center; padding:10px;">Bank List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('banks.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
        <a href="{{ route('banks.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex ">
        <form action="{{ route('banks.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group w-100" style="display: flex; align-items: center;">
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                <button type="submit" class="btn btn-primary mr-1">Search</button>
            </div>
        </form>

        <div class="d-flex">
            <form action="{{ route('banks.exportExcel') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('banks.exportPdf') }}'">PDF</button>
                <button type="submit" class="btn btn-primary">Excel</button>
            </form>
        </div>
    </div>
</div>

<!-- Responsive table wrapper -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>Bank Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banks as $bank)
                <tr>
                    <td>{{ $bank->id }}</td>
                    <td>{{ $bank->bank_name }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('banks.edit', $bank) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('banks.destroy', $bank) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this bank?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $banks->firstItem() }} to {{ $banks->lastItem() }} of {{ $banks->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($banks->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $banks->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $banks->lastPage()) as $i)
            @if ($i == $banks->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $banks->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($banks->hasMorePages())
            <a href="{{ $banks->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $banks->links() }}
    </div>
</div>
</div>
@endsection