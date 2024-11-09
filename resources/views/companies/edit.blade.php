@extends('layouts.app')

@section('title', 'Company Info - Edit')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Edit Company Info</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="company_id">Company ID</label>
            <input type="text" id="company_id" name="company_id" class="form-control" value="{{ old('company_id', $company->company_id) }}" required>
        </div>

        <div class="form-group">
            <label for="name">Company Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
        </div>

        <div class="form-group">
            <label for="tin">TIN</label>
            <input type="text" name="tin" class="form-control" value="{{ old('tin', $company->tin) }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $company->address) }}" required>
        </div>

        <div class="form-group">
            <label for="logo">Logo</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
            @if ($company->logo)
                <img src="{{ asset('storage/company_logos/' . $company->logo) }}" alt="Company Logo" class="img-fluid mt-2">
            @else
                <p>No logo uploaded</p>
            @endif
        </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to list</a>
    </form>
</div>
<script>
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
@endsection