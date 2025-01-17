@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Company Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Company: {{ $company->name }}</h5>

                    <p><strong>Location:</strong> {{ $company->location }}</p>
                    <p><strong>Postal Address:</strong> {{ $company->postal_address }}</p>
                    <p><strong>Postal Code:</strong> {{ $company->postal_code }}</p>
                    <p><strong>Primary Phone:</strong> {{ $company->primary_phone }}</p>
                    <p><strong>Secondary Phone:</strong> {{ $company->secondary_phone }}</p>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Website:</strong> <a href="{{ $company->url }}">{{ $company->url }}</a></p>

                    <div class="text-end">
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary">Edit Company</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection