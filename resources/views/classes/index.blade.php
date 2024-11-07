@extends('layouts.app')

@section('title', 'Class - List')

@section('content')
<div class="container mt-5">
<h2 style="text-align: center; padding:10px;">Class List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('classes.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
        <a href="{{ route('classes.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex">
        <form action="{{ route('classes.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group w-100" style="display: flex; align-items: center;">
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                <button type="submit" class="btn btn-primary mr-1">Search</button>
            </div>
        </form>

        <div class="d-flex">
            <form action="{{ route('classes.exportExcel') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('classes.exportPdf') }}'">PDF</button>
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
                <th>Class Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
           @foreach($classes as $key => $class)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $class->class_name }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('classes.destroy', $class) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $classes->firstItem() }} to {{ $classes->lastItem() }} of {{ $classes->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($classes->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $classes->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $classes->lastPage()) as $i)
            @if ($i == $classes->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $classes->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($classes->hasMorePages())
            <a href="{{ $classes->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $classes->links() }}
    </div>
</div>
</div>
<script>
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
@endsection