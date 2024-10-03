@extends('layouts.app')

@section('title', 'Trainee - Add New')

@section('content')
<div class="container mt-5">
    <h2>Trainee, Add New</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-section">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="asset_name" class="required">Date</label>
                        <input type="date" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="required">Start Time</label>
                        <input type="time" class="form-control" id="start_time"  name="start_time"placeholder="start time" required>
                    </div>
                    <div class="form-group">
                        <label for="finish_time" class="required">Finish Time</label>
                        <input type="time" class="form-control" id="finish_time" name="finish_time" placeholder="finish time" required>
                    </div>
                                        <div class="form-group">
                        <label for="asset_name" class="required">Difference</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">Trainee Name</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>
                    <div class="form-group">
                        <label for="asset_name" class="required">Trainer Name</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('attendance.show') }}" class="btn btn-secondary btn-custom">Back to list</a> <!-- Updated Back to list button -->

            </div>
        </form>
    </div>
</div>
@endsection
