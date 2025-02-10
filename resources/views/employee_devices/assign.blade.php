@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Assign Serial Number to User</h1>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('serialnumbers.assign', $serialNumber) }}" method="POST">
    @csrf

    <div class="row mb-3">
        <label for="user_id" class="col-sm-2 col-form-label">User</label>
        <div class="col-sm-10">
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select a user</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Assign Serial Number</button>
</form>

@endsection
