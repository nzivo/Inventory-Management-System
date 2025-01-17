@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">Edit User Details</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-primary mt-2">View Users</a>
                        </div>
                    </div>

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                                    placeholder="Enter name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" placeholder="Enter email" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter new password (Leave blank to keep unchanged)">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="confirm-password" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="confirm-password" id="confirm-password"
                                    class="form-control" placeholder="Confirm password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="roles" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select name="roles[]" id="roles" class="form-control" multiple="multiple" required>
                                    @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}" {{ isset($userRole[$value]) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn w-100 btn-primary">Update User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection