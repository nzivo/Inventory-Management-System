@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit Company</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">Edit Company Details</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('companies.index') }}" class="btn btn-primary mt-2">View Company
                                Details</a>
                        </div>
                    </div>
                    <form action="{{ route('companies.update', $company->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Company Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $company->name) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="location" class="col-sm-2 col-form-label">Location</label>
                            <div class="col-sm-10">
                                <input type="text" name="location" id="location" class="form-control"
                                    value="{{ old('location', $company->location) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postal_address" class="col-sm-2 col-form-label">Postal Address</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_address" id="postal_address" class="form-control"
                                    value="{{ old('postal_address', $company->postal_address) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postal_code" class="col-sm-2 col-form-label">Postal Code</label>
                            <div class="col-sm-10">
                                <input type="text" name="postal_code" id="postal_code" class="form-control"
                                    value="{{ old('postal_code', $company->postal_code) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="primary_phone" class="col-sm-2 col-form-label">Primary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="primary_phone" id="primary_phone" class="form-control"
                                    value="{{ old('primary_phone', $company->primary_phone) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="secondary_phone" class="col-sm-2 col-form-label">Secondary Phone</label>
                            <div class="col-sm-10">
                                <input type="text" name="secondary_phone" id="secondary_phone" class="form-control"
                                    value="{{ old('secondary_phone', $company->secondary_phone) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $company->email) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="url" class="col-sm-2 col-form-label">Website URL</label>
                            <div class="col-sm-10">
                                <input type="url" name="url" id="url" class="form-control"
                                    value="{{ old('url', $company->url) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Update Company</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection