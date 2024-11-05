@extends('layouts.app')

@section('title', 'Users - List')

@section('content')
<div class="container mt-5">
    <h2 style="text-align: center; padding:10px;">Users List</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Entries selection and Add New button -->
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('users.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
            <a href="{{ route('users.create') }}" class="btn btn-primary ml-2">Add New</a>
        </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex">
            <form action="{{ route('users.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                </div>
            </form>


            <!-- Export buttons -->
            <!-- <div class="d-flex">
              <form action="{{ route('trainee.export') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('trainee.exportPdf') }}'">PDF</button>
                    <button type="submit" class="btn btn-primary">Excel</button>
                </form>
            </div> -->
        </div>
    </div>

    <!-- Responsive table wrapper -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }} <!-- This will display the pagination links -->
        </div>
</div>
@endsection