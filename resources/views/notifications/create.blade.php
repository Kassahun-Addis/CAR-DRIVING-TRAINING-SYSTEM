

@extends('layouts.app')

@section('title', 'Notification - Add New')

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Notification, Add New</h2>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-section">
    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
            <textarea name="content" class="form-control" required></textarea>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
    </form>
    </div>
</div>

<script>
   // Fetch car details when the trainer is selected
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