<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Asset Delivery & Agreement Note</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
            .header { text-align: center; margin-bottom: 30px; }
            .section { margin-bottom: 20px; }
            .signature-block { margin-top: 40px; }
            .signature-line { border-top: 1px solid #000; width: 250px; text-align: center; margin-top: 5px; }
            .logo { text-align: center; margin-bottom: 20px; }
        </style>
    </head>
    <body>

        <div class="logo">
            <img src="{{ public_path('assets/images/fon_logo.png') }}" alt="Company Logo" width="200">
        </div>

        <div class="header">
            <h2>Asset Delivery & Agreement Note</h2>
            <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
        </div>

        <div class="section">
            <p><strong>To:</strong> {{ $serialNumber->user->name }}</p>
            <p><strong>Email:</strong> {{ $serialNumber->user->email }}</p>
        </div>

        <div class="section">
            <p>This document confirms that the following device has been assigned to you:</p>
            <ul>
                <li><strong>Item Name:</strong> {{ $serialNumber->item->name }}</li>
                <li><strong>Serial Number:</strong> {{ $serialNumber->serial_number }}</li>
                <li><strong>Assigned Date:</strong> {{ now()->format('F d, Y') }}</li>
            </ul>
        </div>

        <div class="section">
            <p>By signing below, you acknowledge that you have received the device in good condition and agree to use it responsibly as per the company’s IT policy.</p>
        </div>

        <div class="signature-block">
            <table style="width:100%">
                <th></th>
                <tr>
                    <td>
                        <p>Employee Signature</p>
                        <div class="signature-line">Signature</div>
                        <div class="signature-line">{{ $serialNumber->user->name }}</div>
                    </td>
                    <td style="text-align: right;">
                        <p>Issuer Signature</p>
                        <div class="signature-line">Signature</div>
                        <div class="signature-line">Asset Manager</div>
                    </td>
                </tr>
            </table>
        </div>

    </body>
</html>

