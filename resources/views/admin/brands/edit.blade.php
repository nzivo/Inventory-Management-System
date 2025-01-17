@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit Brand</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Brands</li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">Edit Brand</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('brands.index') }}" class="btn btn-primary mt-2">All Brands</a>
                        </div>
                    </div>
                    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Brand Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $brand->name }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="logo" class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-10">
                                <input type="file" name="logo" id="logo" class="form-control">
                                @if($brand->logo)
                                <img src="{{ asset('storage/logos/' . $brand->logo) }}" alt="logo" width="50">
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Update Brand</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection