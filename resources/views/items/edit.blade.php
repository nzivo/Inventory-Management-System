@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit Asset</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Assets</li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">Edit Asset</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('items.index') }}" class="btn btn-primary mt-2">View Assets</a>
                        </div>
                    </div>

                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Asset Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $item->name) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="type" class="col-sm-2 col-form-label">Item Type</label>
                            <div class="col-sm-10">
                                <input type="text" name="type" id="type" class="form-control"
                                    value="{{ old('type', $item->description) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category_id" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="category_id" class="form-control" required>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $item->category_id ?
                                        'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="branch_id" class="col-sm-2 col-form-label">Branch</label>
                            <div class="col-sm-10">
                                <select name="branch_id" id="branch_id" class="form-control" required>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branch->id == $item->branch_id ? 'selected' :
                                        '' }}>
                                        {{ $branch->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="serial_number" class="col-sm-2 col-form-label">Serial Number</label>
                            <div class="col-sm-10">
                                <input type="text" name="serial_number" class="form-control"
                                    value="{{ old('serial_number', $item->serialNumbers->first()->serial_number ?? '') }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Update Asset</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection