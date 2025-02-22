<!DOCTYPE html>
<html>

<head>
    <title>License Renewal Alert: {{ $item->name }}</title>
</head>

<body>
    <h1>License Renewal Alert: {{ $item->name }}</h1>
    <p>Your license for the product {{ $item->name }} is due for renewal soon.</p>
    <p><strong>License Expiration Date:</strong> {{ $item->license_expiration_date }}</p>
    <p>Please renew the license to avoid interruptions.</p>
</body>

</html>
