@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Manage Companies</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item active">List</li>
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
                            <h5 class="card-title">Company Details</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- Only show the "Create New Company" button if no company exists -->
                            @if(!$company)
                            <a href="{{ route('companies.create') }}" class="btn btn-primary mt-3">Add Company
                                Details</a>
                            @else
                            <!-- If company exists, show the Edit button -->
                            <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning mt-3">Edit
                                Company Details</a>
                            @endif
                        </div>
                    </div>

                    @if ($company)
                    <div class="alert alert-info mt-3">
                        <strong>Company Details:</strong>
                        <ul>
                            <li><strong>Name:</strong> {{ $company->name }}</li>
                            <li><strong>Location:</strong> {{ $company->location }}</li>
                            <li><strong>Postal Address:</strong> {{ $company->postal_address }}</li>
                            <li><strong>Postal Code:</strong> {{ $company->postal_code }}</li>
                            <li><strong>Primary Phone:</strong> {{ $company->primary_phone }}</li>
                            <li><strong>Secondary Phone:</strong> {{ $company->secondary_phone }}</li>
                            <li><strong>Email:</strong> {{ $company->email }}</li>
                            <li><strong>Website URL:</strong> <a href="{{ $company->url }}" target="_blank">{{
                                    $company->url }}</a></li>
                        </ul>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        No company details found. Please create a company.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection