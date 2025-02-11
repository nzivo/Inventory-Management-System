@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Employee Devices</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Assets</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">Employee Devices - Serial Numbers</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('items.create') }}" class="btn btn-primary mt-2">Add New Item</a>
                        </div>
                    </div>

                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th> <!-- Updated this column -->
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Assigned User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serialNumbers as $serialNumber)
                            <tr>
                                <td>{{ $loop->iteration }}</td> <!-- Incremental counter starting from 1 -->
                                <td>{{ $serialNumber->item->name }}</td> <!-- Accessing the item name -->
                                <td>{{ $serialNumber->serial_number }}</td>
                                <td>{{ $serialNumber->status }}</td>
                                <td>{{ $serialNumber->user ? $serialNumber->user->name : 'Not Assigned' }}</td>
                                <td>
                                    @if($serialNumber->user)
                                    <form action="{{ route('serialnumbers.unassign', $serialNumber) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger">Unassign</button>
                                    </form>
                                    @else
                                    <a href="{{ route('serialnumbers.assign.form', $serialNumber) }}"
                                        class="btn btn-secondary">Assign</a>
                                    @endif
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