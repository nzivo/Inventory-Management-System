@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Add New Supplier</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Suppliers</li>
            <li class="breadcrumb-item active">Create</li>
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
                            <h5 class="card-title">Create Supplier</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-success mt-2">All
                                Suppliers</a>
                        </div>
                    </div>

                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf

                        <!-- Supplier Name -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Supplier Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row mb-3">
                            <label for="location" class="col-sm-2 col-form-label">Location</label>
                            <div class="col-sm-10">
                                <input type="text" name="location" id="location" class="form-control">
                            </div>
                        </div>

                        <!-- Postal Address -->
                        <div class="row mb-3">
                            <label for="postal_address" class="col-sm-2 col-form-label">Postal Address</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_address" id="postal_address" class="form-control">
                            </div>
                        </div>

                        <!-- Postal Code -->
                        <div class="row mb-3">
                            <label for="postal_code" class="col-sm-2 col-form-label">Postal Code</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_code" id="postal_code" class="form-control">
                            </div>
                        </div>

                        <!-- Primary Phone -->
                        <div class="row mb-3">
                            <label for="primary_phone" class="col-sm-2 col-form-label">Primary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="primary_phone" id="primary_phone" class="form-control">
                            </div>
                        </div>

                        <!-- Secondary Phone -->
                        <div class="row mb-3">
                            <label for="secondary_phone" class="col-sm-2 col-form-label">Secondary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="secondary_phone" id="secondary_phone" class="form-control">
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>

                        <!-- Website URL -->
                        <div class="row mb-3">
                            <label for="url" class="col-sm-2 col-form-label">Website URL</label>
                            <div class="col-sm-10">
                                <input type="text" name="url" id="url" class="form-control">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Create Supplier</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection