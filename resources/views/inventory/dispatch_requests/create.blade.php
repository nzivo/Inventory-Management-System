@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Create Dispatch Request</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Dispatch Requests</li>
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
                            <h5 class="card-title">Create a New Dispatch Request</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('dispatch_requests.index') }}" class="btn btn-primary mt-2">View Dispatch
                                Requests</a>
                        </div>
                    </div>

                    <form action="{{ route('dispatch_requests.store') }}" method="POST">
                        @csrf

                        <!-- Site Field -->
                        <div class="row mb-3">
                            <label for="site" class="col-sm-2 col-form-label">Site</label>
                            <div class="col-sm-10">
                                <input type="text" name="site" id="site" class="form-control" required>
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="row mb-3">
                            <label for="description" class="col-sm-2 col-form-label">Description (Optional)</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="type" class="col-sm-2 col-form-label">Type of Deployment</label>
                            <div class="col-sm-10">
                                <select id="type" name="type" class="form-control">
                                    <option value="" disabled selected>-- Select Deployment Type --</option>
                                    <option value="New Deployment">New Deployment</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <!-- Serial Numbers Selection -->
                        <h3>Select Serial Numbers</h3>
                        <div class="row mb-3">
                            <label for="serial_numbers" class="col-sm-2 col-form-label">Serial Numbers</label>
                            <div class="col-sm-10">
                                <select name="serial_numbers[]" id="serial_numbers" class="form-control select2" multiple
                                    required>
                                    @foreach($serialNumbers as $serialNumber)
                                    <option value="{{ $serialNumber->id }}">
                                        {{ $serialNumber->serial_number }} ({{ $serialNumber->item->name }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn w-100 btn-primary">Create Dispatch Request</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
