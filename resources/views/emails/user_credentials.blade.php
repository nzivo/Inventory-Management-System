<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Credentials</title>
    <!-- Bootstrap 4.5 CSS for email compatibility -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Inline styling for email clients that may not support embedded styles */
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding-bottom: 20px;
        }

        .email-body {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
        }

        .email-footer {
            text-align: center;
            padding-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Email Container -->
    <div class="email-container">
        <!-- Email Header with Logo -->
        <div class="email-header">
            <!-- Use an absolute URL to the image -->
            <img src="{{ env('APP_URL') . '/assets/images/fon_logo.png' }}" alt="Logo" width="150" height="auto">
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <h3>Hello {{ $user->name }},</h3>
            <p>Welcome to Frontier Inventory Management System! We are excited to have you on board. Below are your
                credentials:</p>

            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>

            <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-danger">Login Now</a>
            </div>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p>If you have any questions, feel free to reach out to our support team.</p>
            <p>&copy; {{ date('Y') }} Frontier Optical Networks. All rights reserved.</p>
        </div>
    </div>

</body>

</html>