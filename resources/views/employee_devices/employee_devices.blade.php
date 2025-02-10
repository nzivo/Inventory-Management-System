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

<table class="table">
    <thead>
        <tr>
            <th>Serial Number</th>
            <th>Status</th>
            <th>Assigned User</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($serialNumbers as $serialNumber)
        <tr>
            <td>{{ $serialNumber->serial_number }}</td>
            <td>{{ $serialNumber->status }}</td>
            <td>{{ $serialNumber->user ? $serialNumber->user->name : 'Not Assigned' }}</td>
            <td>
                <a href="{{ route('serialnumbers.assign.form', $serialNumber) }}" class="btn btn-secondary">Assign</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection