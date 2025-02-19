<div class="row">
    <div class="col-md-6">
        @if($item->item_img)
        <img src="{{ $item->item_img }}" alt="Asset Image"
            style="width: 100%; height: 300px; object-fit: cover; padding:10px;">
        @else
        <p>No Image Available</p>
        @endif
    </div>
    <div class="col-md-6">
        <p><strong>Name:</strong> {{ $item->name }}</p>
        <p><strong>Description:</strong> {{ $item->description }}</p>
        <p><strong>Category:</strong> {{ $item->category->name ?? 'N/A' }}</p>
        <p><strong>Branch:</strong> {{ $item->branch->name ?? 'N/A' }}</p>
        <p><strong>Quantity:</strong> {{ $item->status }}</p>
    </div>
    <div class="col-md-6">
        <p><strong>Created By:</strong> {{ $item->creator->name ?? 'Unknown' }}</p>
    </div>

    <!-- Display Serial Number Details -->
    @if($serialNumber)
    <div class="col-md-6 mt-4">
        <h5 class="fw-bolder">Serial Number Details:</h5>
        <p><strong>Serial Number:</strong> {{ $serialNumber->serial_number }}</p>
        <p><strong>Item Status:</strong> {{ $serialNumber->status }}</p>
    </div>
    @else
    <div class="col-md-6 mt-4">
        <p>No serial number details available.</p>
    </div>
    @endif

    <div class="col-md-12">
        <!-- Display Serial Number Logs -->
        <h6 class="mt-4">Asset History:</h6>
        @if($serialNumber->serialNumberLogs->isEmpty())
        <p>No logs available for this serial number.</p>
        @else
        <table class="table table-condensed table-striped" id="datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($serialNumber->serialNumberLogs as $log)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td><span class="badge bg-secondary">{{ $log->user->name ?? 'Unknown User' }}</span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
