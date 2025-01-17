@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Create New Company</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

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
                        <div class="col-md-8">
                            <h5 class="card-title">Add Company Details</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('companies.index') }}" class="btn btn-primary mt-2">View Company
                                Details</a>
                        </div>
                    </div>
                    <h5 class="card-title">Company Details</h5>

                    <form action="{{ route('companies.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Company Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" placeholder="Company Name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="location" class="col-sm-2 col-form-label">Location</label>
                            <div class="col-sm-10">
                                <input type="text" name="location" class="form-control" placeholder="Location" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postal_address" class="col-sm-2 col-form-label">Postal Address</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_address" class="form-control"
                                    placeholder="Postal Address" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postal_code" class="col-sm-2 col-form-label">Postal Code</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_code" class="form-control" placeholder="Postal Code"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="primary_phone" class="col-sm-2 col-form-label">Primary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="primary_phone" class="form-control" placeholder="Primary Phone"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="secondary_phone" class="col-sm-2 col-form-label">Secondary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="secondary_phone" class="form-control"
                                    placeholder="Secondary Phone">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="url" class="col-sm-2 col-form-label">Company URL</label>
                            <div class="col-sm-10">
                                <input type="url" name="url" class="form-control" placeholder="Company URL">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn w-100 btn-primary">Create Company</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection