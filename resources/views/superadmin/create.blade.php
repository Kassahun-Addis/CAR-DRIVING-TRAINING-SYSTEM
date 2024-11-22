<!-- resources/views/superadmin/companies/create.blade.php -->
@extends('layouts.app')

@section('title', 'Create Company')

@section('content')
<div class="container mt-5">
    <h2>Create New Company</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-section">
    <form action="{{ route('superadmin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="company_id">Company ID</label>
            <input type="text" name="company_id" class="form-control" value="{{ old('company_id') }}" required>
        </div>

        <div class="form-group">
            <label for="name">Company Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="tin">TIN</label>
            <input type="text" name="tin" class="form-control" value="{{ old('tin') }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
        </div>

        <div class="form-group">
            <label for="logo">Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-custom">Save</button>
            <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
            <a href="{{ route('superadmin.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
        </div>      
    </form>
    </div>
</div>
@endsection