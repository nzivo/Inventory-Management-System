<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Low Stock Alert</title>
    </head>
    <body>
        <h2>⚠️ Low Stock Alert</h2>
        <p>The following items are grouped by category and their current stock levels:</p>

        @php
            // Group items by category_name or category->name
            $grouped = $lowStockItems->groupBy('category.name'); // adjust if you're using category_name directly

        @endphp

        @foreach ($grouped as $categoryName => $items)
            <h3 style="color: #2c3e50;">Category: {{ $categoryName ?? 'Uncategorized' }}</h3>
            <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th>Item Name</th>
                        <th>Remaining Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $stock = $item->stock;

                            if ($stock <= 5) {
                                $label = 'Critical';
                                $bgColor = '#dc3545'; // red
                                $rowColor = '#f8d7da';
                                $textColor = 'white';
                            } elseif ($stock <= 15) {
                                $label = 'Warning';
                                $bgColor = '#fd7e14'; // orange
                                $rowColor = '#fff3cd';
                                $textColor = '#212529';
                            } elseif ($stock <= 20) {
                                $label = 'Low';
                                $bgColor = '#ffc107'; // yellow
                                $rowColor = '#fffbea';
                                $textColor = '#212529';
                            } else {
                                $label = 'In Stock';
                                $bgColor = '#28a745'; // green
                                $rowColor = '#e6ffed';
                                $textColor = 'white';
                            }
                        @endphp
                        <tr style="background-color: {{ $rowColor }};">
                            <td>{{ $item->name }}</td>
                            <td style="text-align: center;">
                                <strong>{{ $stock }}</strong>
                                <span style="background-color: {{ $bgColor }}; color: {{ $textColor }}; padding: 3px 6px; border-radius: 4px; font-size: 12px;">
                                    {{ $label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br/>
        @endforeach
    </body>
</html>




