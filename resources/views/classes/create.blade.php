@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Classes</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
    <div class="alert alert-success" id="success-message">
        {{ session('success') }}
    </div>
    @endif
    <div class="form-section">
    <form action="{{ route('classes.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="class_name">Class Name</label>
            <input type="text" name="class_name" class="form-control" required>
        </div>
        <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-custom">Save</button>
                <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
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