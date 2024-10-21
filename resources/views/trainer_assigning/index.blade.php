@extends('layouts.app')

@section('title', 'Trainers Assigning - List')

@section('content')
<div class="container mt-5">
<h2 style="text-align: center; padding:10px;">Trainers Assigning List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('trainer_assigning.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
        <a href="{{ route('trainer_assigning.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

    <!-- Search and Export buttons -->
    <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
        <form action="{{ route('trainer_assigning.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group w-100" style="display: flex; align-items: center;">
                <!-- Search input takes more space on small devices -->
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">

                <!-- Search button -->
                <button type="submit" class="btn btn-primary mr-1">Search</button>

                <!-- Export dropdown on small devices -->
                <div class="d-block d-md-none dropdown ml-1">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="printAllBankDetails()">PDF</a>
                        <a class="dropdown-item" href="{{ route('trainee.export') }}">Excel</a>
                    </div>
                </div>

                <!-- Separate buttons for larger devices -->
                <div class="d-none d-md-block ml-1">
                    <button type="button" class="btn btn-primary" onclick="printAllBankDetails()">PDF</button>
                    <button type="button" class="btn btn-primary ml-1" onclick="window.location.href='{{ route('trainee.export') }}'">Excel</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Responsive table wrapper -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Trainee Name</th>
                <th>Trainer Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Category</th>
                <th>Car Name</th>
                <th>Plate No</th>
                <!-- <th>Training Car</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers_assigning as $key => $trainer_assigning)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer_assigning->trainee_name }}</td>
                    <td>{{ $trainer_assigning->trainer_name }}</td>
                    <td>{{ $trainer_assigning->start_date }}</td>
                    <td>{{ $trainer_assigning->end_date }}</td>
                    <td>{{ $trainer_assigning->category_id }}</td>
                    <td>{{ $trainer_assigning->plate_no }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('trainer_assigning.edit', $trainer_assigning->assigning_id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('trainer_assigning.destroy', $trainer_assigning->assigning_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $trainers_assigning->firstItem() }} to {{ $trainers_assigning->lastItem() }} of {{ $trainers_assigning->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($trainers_assigning->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $trainers_assigning->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $trainers_assigning->lastPage()) as $i)
            @if ($i == $trainers_assigning->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $trainers_assigning->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($trainers_assigning->hasMorePages())
            <a href="{{ $trainers_assigning->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $trainers_assigning->links() }}
    </div>
</div>
</div>
@endsection