@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>All Dispatch Requests</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Dispatch Requests</li>
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
                            <h5 class="card-title">All Dispatch Requests</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('dispatch_requests.create') }}" class="btn btn-primary mt-3">
                                Dispatch Request</a>
                        </div>
                    </div>

                    <table class="table table-condensed table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dispatch Number</th>
                                <th>User</th>
                                <th>Site</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispatchRequests as $index => $dispatchRequest)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dispatchRequest->dispatch_number }}</td>
                                <td>{{ $dispatchRequest->user->name }}</td>
                                <td>{{ $dispatchRequest->site }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $dispatchRequest->status === 'approved' ? 'success' : ($dispatchRequest->status === 'denied' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($dispatchRequest->status) }}
                                    </span>
                                </td>
                                {{-- <td>{{ $dispatchRequest->approver->name }}</td> --}}
                                @if($dispatchRequest->approver->name === '')
                                <td>{{ Null }}</td>
                                @else
                                <td>{{ $dispatchRequest->approver->name }}</td>
                                @endif


                                <td>{{ $dispatchRequest->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dispatch_requests.show', $dispatchRequest->id) }}"
                                        class="btn btn-info m-1">View</a>
                                    @if ($dispatchRequest->status == 'pending')
                                    <a href="{{ route('dispatch_requests.edit', $dispatchRequest->id) }}"
                                        class="btn btn-warning m-1">Edit</a>
                                    <form action="{{ route('dispatch_requests.destroy', $dispatchRequest->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif

                                    <!-- Show Print Gate Pass button only if approved -->
                                    @if ($dispatchRequest->status == 'approved')
                                    <a href="{{ route('dispatch_requests.printGatePass', $dispatchRequest->id) }}"
                                        class="btn btn-secondary m-1">Print Gate Pass</a>
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

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="viewModalLabel">Dispatch Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewDispatchRequest(dispatchId) {
        // Set the modal title dynamically to the dispatch number
        $('#viewModalLabel').text('Dispatch Request #' + dispatchId);

        $.ajax({
            url: "{{ route('dispatch_requests.show', ':id') }}".replace(':id', dispatchId),
            type: 'GET',
            success: function(response) {
                // Populate the modal body with the dispatch request details
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection
