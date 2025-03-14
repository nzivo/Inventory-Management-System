<!DOCTYPE html>
<html>

<head>
    <title>New Dispatch Request: {{ $dispatchRequest->dispatch_number }}</title>
</head>

<body>
    <h1>New Dispatch Request: {{ $dispatchRequest->dispatch_number }}</h1>
    <p>A new dispatch request has been created by {{ $dispatchRequest->user->name }}.</p>
    <p><strong>Site:</strong> {{ $dispatchRequest->site }}</p>
    <p><strong>Description:</strong> {{ $dispatchRequest->description }}</p>
    <p><strong>Status:</strong> {{ ucfirst($dispatchRequest->status) }}</p>
    <p><strong>Dispatch Number:</strong> {{ $dispatchRequest->dispatch_number }}</p>
    <p>Serial Numbers:</p>
    <ul>
        @foreach ($dispatchRequest->serialNumbers as $serialNumber)
        <li>{{ $serialNumber->serial_number_id}}</li>
        @endforeach
    </ul>
</body>

</html>