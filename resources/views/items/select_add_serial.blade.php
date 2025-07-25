@extends('layouts.dashboard')

@section('content')
<div class="pagetitle">
    <h1>Select Item to Add Serial Numbers</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body pt-4">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Available Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->model_number ?? 'N/A' }}</td>
                                <td>{{ $item->available_quantity }}</td>
                                <td>
                                    <a href="{{ route('items.add_serials.form', ['id' => $item->id]) }}" class="btn btn-sm btn-primary">
                                        Add Serial Numbers
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @if($items->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No items available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
