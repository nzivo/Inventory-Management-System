<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Low Stock Alert</title>
    </head>
    <body>
        <h2>⚠️ Low Stock Alert</h2>
        <p>The following items have stock less than 5:</p>

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
                        <tr style="background-color: {{ $item->stock < 3 ? '#f8d7da' : '#fff3cd' }};">
                            <td>{{ $item->name }}</td>
                            {{-- <td style="text-align: center;"><strong>{{ $item->stock }}</strong></td> --}}
                            <td style="text-align: center;">
                                <strong>{{ $item->stock }}</strong>
                                @if ($item->stock < 2)
                                    <span style="background-color:#dc3545; color:white; padding:3px 6px; border-radius:4px; font-size: 12px;">Critical</span>
                                @elseif ($item->stock < 4)
                                    <span style="background-color:#ffc107; color:#212529; padding:3px 6px; border-radius:4px; font-size: 12px;">Warning</span>
                                @else
                                    <span style="background-color:#28a745; color:white; padding:3px 6px; border-radius:4px; font-size: 12px;">Low</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br/>
        @endforeach
    </body>
</html>



