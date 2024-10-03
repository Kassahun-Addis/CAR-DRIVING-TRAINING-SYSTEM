@extends('layouts.app')

@section('title', 'Trainee - Add New')

@section('content')
<div class="container mt-5">
    <h2>Trainee, Add New</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-section">
        <form action="{{ route('trainee.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="asset_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="category" class="required">DOB</label>
                        <input type="date" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price" class="required">Gender</label>
                        <select class="form-control" id="purchase_price" name="purchase_price" required>
                            <option value="">Please select</option>
                            <option value="active">Male</option>
                            <option value="inactive">Female</option>
                        </select> 
                    </div>
                    <div class="form-group">
                        <label for="department" class="required">Address</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="status" class="required">Liscence Type</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Please select</option>
                            <option value="active">Motor Cycle</option>
                            <option value="inactive">Car</option>
                            <option value="inactive">Truck</option>
                        </select>
                    </div>
                </div>
                    <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="status" class="required">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Please select</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="inactive">Compeletd</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="serial_no">Start Date</label>
                        <input type="date" class="form-control" id="serial_no" name="serial_no">
                    </div>
                    <div class="form-group">
                        <label for="description">End date</label>
                        <input type="date" class="form-control" id="description" name="description"></input type="date">
                    </div>
                    <div class="form-group">
                        <label for="status" class="required">Payment Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Please select</option>
                            <option value="active">paid</option>
                            <option value="inactive">pending</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainee.show') }}" class="btn btn-secondary btn-custom">Back to list</a> <!-- Updated Back to list button -->

            </div>
        </form>
    </div>
</div>
@endsection
