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
                        <label for="asset_name" class="required">Full Name</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
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
                        <label for="asset_name" class="required">Nationality</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="department" class="required">City</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="department" class="required">Sub City</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="department" class="required">Woreda</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">House No</label>
                        <input type="number" class="form-control" id="asset_name" name="asset_name" required>
                    </div> 
                    <div class="form-group">
                        <label for="asset_name" class="required">Phone No</label>
                        <input type="number" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">P.O.Box</label>
                        <input type="number" class="form-control" id="asset_name" name="asset_name" required>
                    </div>                     
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="asset_name" class="required">Birth Place</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="category" class="required">DOB</label>
                        <input type="date" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">Existing Driving License No</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name">
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
                    <div class="form-group">
                        <label for="serial_no">Education Level</label>
                        <input type="text" class="form-control" id="serial_no" name="serial_no">
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">Any Case(Disease)</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name">
                    </div>          
                    <div class="form-group">
                    <label for="department" class="required">Blood Type</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="">Please select</option>
                            <option value="active">A</option>
                            <option value="active">B</option>
                            <option value="active">O</option>
                            <option value="active">AB</option>
                            <option value="active">A+</option>
                            <option value="active">B+</option>
                            <option value="active">A-</option>
                            <option value="active">B-</option>
                            <option value="active">O+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Reciept No</label>
                        <input type="text" class="form-control" id="description" name="description"></input type="date">
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
