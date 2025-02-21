@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Asset Details</h3>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $item->name }}</p>
            <p><strong>Description:</strong> {{ $item->description }}</p>
            <p><strong>Category:</strong> {{ $item->category->name }}</p>
            <p><strong>Branch:</strong> {{ $item->branch->name }}</p>
            <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
            <p><strong>Available:</strong> {{ $item->available_quantity }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'danger' }}">{{
                    ucfirst($item->status) }}</span></p>
        </div>
        <div class="card-footer">
            <a href="{{ route('asset.assets') }}" class="btn btn-secondary">Back</a>
            @if(auth()->user()->can('edit-asset'))
            <a href="{{ route('asset.editAsset', $item->id) }}" class="btn btn-primary">Edit</a>@endif
            @if(auth()->user()->can('add-asset-item'))
            <a href="{{ route('serialnumbers.create', $item->id) }}" class="btn btn-success btn-sm">Add Items</a>@endif
        </div>
    </div>
</div>
@endsection
