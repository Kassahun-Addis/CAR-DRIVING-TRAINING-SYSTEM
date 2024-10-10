@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Bank</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('banks.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection