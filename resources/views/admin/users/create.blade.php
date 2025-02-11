@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Create New User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active">Create</li>
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
                            <h5 class="card-title">Create a New User</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-primary mt-2">View Users</a>
                        </div>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter email" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter password" value="{{ $generatedPassword }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="confirm-password" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="confirm-password" id="confirm-password"
                                    class="form-control" placeholder="Confirm password" value="{{ $generatedPassword }}"
                                    required>
                            </div>
                        </div>

                        <!-- Designation Dropdown -->
                        <div class="row mb-3">
                            <label for="designation_id" class="col-sm-2 col-form-label">Designation</label>
                            <div class="col-sm-10">
                                <select name="designation_id" id="designation_id" class="form-control" required>
                                    @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}">
                                        {{ $designation->designation_name }} - {{ $designation->department_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="roles" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select name="roles[]" id="roles" class="form-control" multiple="multiple" required>
                                    @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="inactive" selected>Inactive</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn w-100 btn-primary">Create User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection