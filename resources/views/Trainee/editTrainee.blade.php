@extends('layouts.app')

@section('title', 'Trainee - Edit')

@section('content')
<div class="container mt-5">
    <h2>Trainee - Edit</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-section">
        <form action="{{ route('trainee.update', $trainee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="full_name" class="required">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $trainee->full_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="required">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Please select</option>
                            <option value="Male" {{ $trainee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $trainee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $trainee->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select> 
                    </div>
                    <div class="form-group">
                        <label for="nationality" class="required">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', $trainee->nationality) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="city" class="required">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $trainee->city) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="sub_city" class="required">Sub City</label>
                        <input type="text" class="form-control" id="sub_city" name="sub_city" value="{{ old('sub_city', $trainee->sub_city) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="woreda" class="required">Woreda</label>
                        <input type="text" class="form-control" id="woreda" name="woreda" value="{{ old('woreda', $trainee->woreda) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="house_no" class="required">House No</label>
                        <input type="number" class="form-control" id="house_no" name="house_no" value="{{ old('house_no', $trainee->house_no) }}" required>
                    </div> 
                    <div class="form-group">
                        <label for="phone_no" class="required">Phone No</label>
                        <input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no', $trainee->phone_no) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="po_box" class="required">P.O.Box</label>
                        <input type="number" class="form-control" id="po_box" name="po_box" value="{{ old('po_box', $trainee->po_box) }}" required>
                    </div>                     
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="birth_place" class="required">Birth Place</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place', $trainee->birth_place) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="dob" class="required">DOB</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $trainee->dob) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="driving_license_no" class="required">Existing Driving License No</label>
                        <input type="text" class="form-control" id="driving_license_no" name="driving_license_no" value="{{ old('driving_license_no', $trainee->existing_driving_lic_no) }}">
                    </div> 
                    <div class="form-group">
                        <label for="license_type" class="required">License Type</label>
                        <select class="form-control" id="license_type" name="license_type" required>
                            <option value="">Please select</option>
                            <option value="Motor Cycle" {{ $trainee->license_type == 'Motor Cycle' ? 'selected' : '' }}>Motor Cycle</option>
                            <option value="Car" {{ $trainee->license_type == 'Car' ? 'selected' : '' }}>Car</option>
                            <option value="Truck" {{ $trainee->license_type == 'Truck' ? 'selected' : '' }}>Truck</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="education_level">Education Level</label>
                        <input type="text" class="form-control" id="education_level" name="education_level" value="{{ old('education_level', $trainee->education_level) }}">
                    </div>
                    <div class="form-group">
                        <label for="disease" class="required">Any Case (Disease)</label>
                        <input type="text" class="form-control" id="disease" name="disease" value="{{ old('disease', $trainee->any_case) }}">
                    </div>          
                    <div class="form-group">
                        <label for="blood_type" class="required">Blood Type</label>
                        <select class="form-control" id="blood_type" name="blood_type" required>
                            <option value="">Please select</option>
                            <option value="A" {{ $trainee->blood_type == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ $trainee->blood_type == 'B' ? 'selected' : '' }}>B</option>
                            <option value="O" {{ $trainee->blood_type == 'O' ? 'selected' : '' }}>O</option>
                            <option value="AB" {{ $trainee->blood_type == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="A+" {{ $trainee->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="B+" {{ $trainee->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="A-" {{ $trainee->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B-" {{ $trainee->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ $trainee->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receipt_no">Receipt No</label>
                        <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{ old('receipt_no', $trainee->receipt_no) }}">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('trainee.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
        </form>
    </div>
</div>
@endsection