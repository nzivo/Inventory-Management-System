<!DOCTYPE html>
<html>

<head>
    <title>Low Stock Alert: {{ $item->name }}</title>
</head>

<body>
    <h1>Low Stock Alert: {{ $item->name }}</h1>
    <p>The available quantity for {{ $item->name }} has dropped below the threshold.</p>
    <p><strong>Available Quantity:</strong> {{ $item->available_quantity }}</p>
    <p><strong>Threshold:</strong> {{ $item->threshold }}</p>
    <p>Please restock soon!</p>
</body>

</html>
