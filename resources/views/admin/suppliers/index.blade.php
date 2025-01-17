@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Manage Suppliers</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Suppliers</li>
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
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">All Suppliers</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('suppliers.create') }}" class="btn btn-success mt-2">Add
                                Supplier</a>
                        </div>
                    </div>

                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier->id) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this supplier?');">
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