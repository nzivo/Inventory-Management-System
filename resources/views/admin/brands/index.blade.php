@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Manage Brands</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Brands</li>
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
                            <h5 class="card-title">All Brands</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('brands.create') }}" class="btn btn-primary mt-2">Add New Brand</a>
                        </div>
                    </div>
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $key => $brand)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $brand->name }}</td>
                                <td><img src="{{ asset('storage/logos/' . $brand->logo) }}" alt="logo" width="50"></td>
                                <td>
                                    <a href="{{ route('brands.edit', $brand->id) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection