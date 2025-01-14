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
</div>