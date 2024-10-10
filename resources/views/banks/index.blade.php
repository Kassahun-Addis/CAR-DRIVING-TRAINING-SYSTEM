@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bank List</h2>
    <a href="{{ route('banks.create') }}" class="btn btn-primary">Add Bank</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bank Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banks as $bank)
                <tr>
                    <td>{{ $bank->id }}</td>
                    <td>{{ $bank->bank_name }}</td>
                    <td>
                        <a href="{{ route('banks.edit', $bank) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('banks.destroy', $bank) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection