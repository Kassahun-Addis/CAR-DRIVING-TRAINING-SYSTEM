@extends('layouts.app')

@section('title', 'Trainers - List')

@section('content')
<div class="container mt-5">
<h2 style="text-align: center; padding:10px;">Trainers List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Entries selection and Add New button -->
    <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
        <!-- Per Page Selection -->
        <form action="{{ route('trainers.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
        <a href="{{ route('trainers.create') }}" class="btn btn-primary ml-2">Add New</a>
    </div>

    <!-- Search and Export buttons -->
    <div class="col-12 col-md-6 d-flex">
        <form action="{{ route('trainers.index') }}" method="GET" class="form-inline" style="flex: 1;">
            <div class="form-group w-100" style="display: flex; align-items: center;">
                <!-- Search input takes more space on small devices -->
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                <!-- Search button -->
                <button type="submit" class="btn btn-primary mr-1">Search</button>
            </div>
        </form>

            <div class="d-flex">
              <form action="{{ route('trainers.export') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('trainers.exportPdf') }}'">PDF</button>
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
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Experience (Years)</th>
                <th>Training Type</th>
                <th>Category</th>
                <th>Car Name</th>
                <th>Plate No</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers as $key => $trainer)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer->trainer_name }}</td>
                    <td>{{ $trainer->phone_number }}</td>
                    <td>{{ $trainer->email }}</td>
                    <td>{{ $trainer->experience }}</td>
                    <td>{{ $trainer->training_type }}</td>
                    <td>{{ $trainer->category }}</td>
                    <td>{{ $trainer->car_name }}</td>
                    <td>{{ $trainer->plate_no }}</td>
                    <td class="text-nowrap">
                        <!-- Toggle Active/Inactive Button -->
                        <button class="btn btn-sm toggle-status {{ $trainer->status === 'active' ? 'btn-success' : 'btn-secondary' }}" data-id="{{ $trainer->id }}">
                            {{ $trainer->status === 'active' ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('trainers.edit', $trainer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('trainers.destroy', $trainer->id) }}" method="POST" style="display:inline;">
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
    Showing {{ $trainers->firstItem() }} to {{ $trainers->lastItem() }} of {{ $trainers->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($trainers->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $trainers->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $trainers->lastPage()) as $i)
            @if ($i == $trainers->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $trainers->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($trainers->hasMorePages())
            <a href="{{ $trainers->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $trainers->links() }}
    </div>
</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const currentStatus = this.textContent.trim();
            const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
            const trainerId = this.getAttribute('data-id');

            // Toggle the button text and class
            this.textContent = newStatus;
            this.classList.toggle('btn-success');
            this.classList.toggle('btn-secondary');

            // Send an AJAX request to update the status in the database
            fetch(`/trainers/${trainerId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus.toLowerCase() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    console.error('Error updating status:', data.message);
                    // Optionally, revert the button state if the update fails
                    this.textContent = currentStatus;
                    this.classList.toggle('btn-success');
                    this.classList.toggle('btn-secondary');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                // Optionally, revert the button state if the update fails
                this.textContent = currentStatus;
                this.classList.toggle('btn-success');
                this.classList.toggle('btn-secondary');
            });
        });
    });
});
</script>
@endsection