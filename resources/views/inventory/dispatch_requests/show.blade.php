@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Dispatch Request Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dispatch_requests.index') }}">Dispatch Requests</a></li>
            <li class="breadcrumb-item active">View</li>
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
                            <h5 class="card-title">Dispatch Request #{{ $dispatchRequest->dispatch_number }}</h5>
                        </div>
                        @if($dispatchRequest->status === 'pending')
                        <div class="col-md-2 mt-3">
                            @if(auth()->user()->can('approve-dispatch-request'))
                            <form action="{{ route('dispatch_requests.updateStatus', $dispatchRequest->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            @endif

                            @if(auth()->user()->can('approve-dispatch-request'))
                            <form action="{{ route('dispatch_requests.updateStatus', $dispatchRequest->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="denied">
                                <button type="submit" class="btn btn-danger">Deny</button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>

                    <table class="table">
                        <tr>
                            <th>Dispatch Number</th>
                            <td>{{ $dispatchRequest->dispatch_number }}</td>
                        </tr>
                        <tr>
                            <th>Site</th>
                            <td>{{ $dispatchRequest->site }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{ $dispatchRequest->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $dispatchRequest->status === 'approved' ? 'success' : ($dispatchRequest->status === 'denied' ? 'danger' : 'warning') }} ">
                                    {{ ucfirst($dispatchRequest->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $dispatchRequest->description ?? 'No description available' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $dispatchRequest->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Approved At</th>
                            <td>{{ $dispatchRequest->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>

                    <h6>Items</h6>
                    <ul>
                        @foreach($dispatchRequest->serialNumbers as $serial)
                        <li>
                            {{ $serial->serialNumber->serial_number }} -
                            @if($serial->serialNumber->item)
                            ({{ $serial->serialNumber->item->name }})
                            @else
                            (No item associated)
                            @endif
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('dispatch_requests.index') }}" class="btn btn-secondary">Back to Dispatch
                        Requests</a>

                    @if ($dispatchRequest->status == 'approved')
                    <a href="{{ route('dispatch_requests.printGatePass', $dispatchRequest->id) }}"
                        class="btn btn-success m-1 align-right">Print Gate
                        Pass</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection