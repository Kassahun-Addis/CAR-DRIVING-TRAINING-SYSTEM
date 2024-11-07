@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Class</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(@success)
    <div class="alert alert-success" id="success-message">
        {{ session('success') }}
    </div>
    @endif
    <div class="form-section">
    <form action="{{ route('classes.update', $class->class_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="class_name">Bank Name</label>
            <input type="text" name="class_name" class="form-control" value="{{ $class->class_name }}" required>
        </div>

        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-custom">Back to list</a>
            </div>
    </form>
</div>
</div>
<script>
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
@endsection