@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Employee Devices - Serial Numbers</h1>
</div>

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

<table class="table" id="datatable">
    <thead>
        <tr>
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
                <a href="{{ route('serialnumbers.assign.form', $serialNumber) }}" class="btn btn-secondary">Assign</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
