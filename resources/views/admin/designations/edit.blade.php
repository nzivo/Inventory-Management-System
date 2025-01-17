@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit Designation</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Designations</li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">Edit Designation</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('designations.index') }}" class="btn btn-primary mt-2">All
                                Designations</a>
                        </div>
                    </div>
                    <form action="{{ route('designations.update', $designation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Designation Name -->
                        <div class="row mb-3">
                            <label for="designation_name" class="col-sm-2 col-form-label">Designation Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="designation_name" id="designation_name" class="form-control"
                                    value="{{ $designation->designation_name }}" required>
                            </div>
                        </div>

                        <!-- Department Name -->
                        <div class="row mb-3">
                            <label for="department_name" class="col-sm-2 col-form-label">Department</label>
                            <div class="col-sm-10">
                                <input type="text" name="department_name" id="department_name" class="form-control"
                                    value="{{ $designation->department_name }}" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Update Designation</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection