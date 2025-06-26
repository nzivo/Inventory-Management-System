@extends('layouts.dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Edit Dispatch Request</h1>
    </div>

    <form action="{{ route('dispatch-requests.update', $dispatchRequest->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>User:</label>
            <input type="text" class="form-control" value="{{ $dispatchRequest->user->name ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label>Serial Numbers:</label>
            <ul>
                @foreach($dispatchRequest->serialNumbers as $serial)
                    <li>
                        {{ $serial->serial_number }} -
                        {{ $serial->item->name ?? 'Item not found' }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Example: Status Update --}}
        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $dispatchRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $dispatchRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="denied" {{ $dispatchRequest->status == 'denied' ? 'selected' : '' }}>Denied</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Dispatch</button>
    </form>
@endsection
