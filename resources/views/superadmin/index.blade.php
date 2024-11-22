<!-- resources/views/superadmin/index.blade.php -->
@extends('layouts.app')

@section('title', 'All Companies')

@section('content')
<div class="container mt-5">
    <h2 style="text-align: center; padding:10px;">Company List</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Entries selection and Add New button -->
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('superadmin.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
            <a href="{{ route('superadmin.create') }}" class="btn btn-primary ml-2">Add New</a>
        </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex">
            <form action="{{ route('superadmin.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Responsive table wrapper -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Company ID</th>
                    <th>Company Name</th>
                    <th>TIN</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $index => $company)
                    <tr>
                        <td>{{ $companies->firstItem() + $index }}</td>
                        <td>{{ $company->company_id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->tin }}</td>
                        <td>{{ $company->phone }}</td>
                        <td>{{ $company->email }}</td>
                        <td>{{ $company->address }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('superadmin.edit', $company->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('superadmin.destroy', $company->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this company?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Showing entries information -->
    <div class="mt-3">
        Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} of {{ $companies->total() }} entries
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            {{ $companies->links() }}
        </div>
    </div>
</div>

<script>
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