@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>All Assets</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Assets</li>
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
                            <h5 class="card-title">All Assets</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            {{-- @if(auth()->user()->can('create-assets')) --}}
                            <a href="{{ route('items.create') }}" class="btn btn-primary mt-3">Create New
                                Asset</a>
                            {{-- @endif --}}
                        </div>
                    </div>

                    <table class="table table-condensed table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Serial Number</th>
                                <th>Serial Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($items as $item)
                            @foreach($item->serialNumbers as $serialNumber)
                            <tr>
                                <td>{{ $counter++ }}</td> <!-- Incrementing the counter manually -->
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if($item->thumbnail_img)
                                    <img src="{{ $item->thumbnail_img }}" alt="Thumbnail"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                <td>{{ $item->branch->name ?? 'N/A' }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->creator->name ?? 'Unknown' }}</td>
                                <td>{{ $serialNumber->serial_number }}</td>
                                <td>{{ $serialNumber->status }}</td>
                                <td>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary m-1">Edit</a>
                                    <button class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewAsset({{ $item->id }}, {{ $serialNumber->id }}, '{{ $item->name }}', '{{ $serialNumber->serial_number }}')">View</button>
                                </td>
                            </tr>
                            @endforeach
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
                <h5 class="modal-title fw-bolder" id="viewModalLabel">Asset Details</h5>
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
    function viewAsset(itemId, serialId, itemName, serialNumber) {
        // Set the modal title dynamically to the asset's name and serial number
        $('#viewModalLabel').text(itemName + ' (Serial: ' + serialNumber + ')');

        $.ajax({
            url: "{{ route('items.show', ':id') }}".replace(':id', itemId),
            type: 'GET',
            data: { serial_id: serialId }, // Pass the serial number ID
            success: function(response) {
                // Populate the modal body with the asset details
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection
