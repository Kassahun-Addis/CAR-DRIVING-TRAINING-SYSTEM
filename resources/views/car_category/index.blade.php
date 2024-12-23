@extends('layouts.app')

@section('title', 'Car Category - List')

@section('content')
<div class="container mt-5">
<h2 style="text-align: center; padding:10px;">Car Category List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" id="success-alert"> 
            {{ session('success') }}
        </div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('car_category.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
        <a href="{{ route('car_category.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex">
        <form action="{{ route('car_category.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group w-100" style="display: flex; align-items: center;">
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                <button type="submit" class="btn btn-primary mr-1">Search</button>
            </div>
        </form>

        <div class="d-flex">
            <form action="{{ route('car_category.exportExcel') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('car_category.exportPdf') }}'">PDF</button>
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
                <th>No</th>
                <th>Car Category Name</th>
                <th>Price</th>
                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin'))
                <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($CarCategorys as $key => $CarCategory)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $CarCategory->car_category_name }}</td>
                    <td>{{ number_format($CarCategory->price, 2) }}</td>
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin'))
                    <td class="text-nowrap">
                        <a href="{{ route('car_category.edit', $CarCategory) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('car_category.destroy', $CarCategory) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this car category?')">Delete</button>
                        </form>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $CarCategorys->firstItem() }} to {{ $CarCategorys->lastItem() }} of {{ $CarCategorys->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($CarCategorys->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $CarCategorys->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $CarCategorys->lastPage()) as $i)
            @if ($i == $CarCategorys->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $CarCategorys->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($CarCategorys->hasMorePages())
            <a href="{{ $CarCategorys->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $CarCategorys->links() }}
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('success-alert')) {
            setTimeout(function() {
                document.getElementById('success-alert').style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection