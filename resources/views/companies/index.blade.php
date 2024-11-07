@extends('layouts.app')

@section('title', 'Company Info - List')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Company Info - List</h2>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Add New Company</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>NO</th>
                <th>Company ID</th>
                <th>Company Name</th>
                <th>TIN</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Logo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $index => $company)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $company->company_id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->tin }}</td>
                    <td>{{ $company->phone }}</td>
                    <td>{{ $company->email }}</td>
                    <td>{{ $company->address }}</td>
                    <td>{{ $company->logo }}</td>


                    <td class="text-nowrap">
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection