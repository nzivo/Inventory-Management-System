<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Low Stock Alert</title>
    </head>
    <body>
        <h2>⚠️ Low Stock Alert</h2>
        <p>The following assets are grouped by category and show current available stock levels:</p>

        @php
            $grouped = $lowStockItems->groupBy('category_name');
        @endphp

        @foreach ($grouped as $categoryName => $itemsInCategory)
            <h3 style="color: #2c3e50;">📦 Category: {{ $categoryName ?? 'Uncategorized' }}</h3>

            @php
                $brandOrSubGrouped = $itemsInCategory->groupBy('group_name');
            @endphp

            @foreach ($brandOrSubGrouped as $groupName => $items)
                <h4 style="margin-left: 10px;">🔹 Brand/Subcategory: {{ $groupName }}</h4>
                <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 95%; margin-left: 20px;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th>Item Name</th>
                            <th>Remaining Stock</th>
                            <th>Priority</th>
                            <th>Stock Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @php
                                $stock = $item->stock;
                                $maxStock = 50; // optional cap for progress visualization

                                if ($stock <= 5) {
                                    $label = 'Critical';
                                    $emoji = '❗';
                                    $bgColor = '#dc3545';
                                    $rowColor = '#f8d7da';
                                    $textColor = 'white';
                                } elseif ($stock <= 15) {
                                    $label = 'Warning';
                                    $emoji = '⚠️';
                                    $bgColor = '#fd7e14';
                                    $rowColor = '#fff3cd';
                                    $textColor = '#212529';
                                } elseif ($stock <= 20) {
                                    $label = 'Low';
                                    $emoji = '📉';
                                    $bgColor = '#ffc107';
                                    $rowColor = '#fffbea';
                                    $textColor = '#212529';
                                } else {
                                    $label = 'In Stock';
                                    $emoji = '✅';
                                    $bgColor = '#28a745';
                                    $rowColor = '#e6ffed';
                                    $textColor = 'white';
                                }

                                $barWidth = min(100, ($stock / $maxStock) * 100);
                            @endphp
                            <tr style="background-color: {{ $rowColor }};">
                                <td>{{ $item->name }}</td>
                                <td style="text-align: center;"><strong>{{ $stock }}</strong></td>
                                <td style="text-align: center;">
                                    <span style="background-color: {{ $bgColor }}; color: {{ $textColor }}; padding: 3px 6px; border-radius: 4px; font-size: 12px;">
                                        {{ $emoji }} {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    <div style="width: 100%; background: #e0e0e0; border-radius: 4px;">
                                        <div style="width: {{ $barWidth }}%; background: {{ $bgColor }}; height: 10px; border-radius: 4px;"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br/>
            @endforeach
        @endforeach
    </body>
</html>
