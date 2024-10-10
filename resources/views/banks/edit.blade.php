@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Bank</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('banks.update', $bank->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" value="{{ $bank->bank_name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection