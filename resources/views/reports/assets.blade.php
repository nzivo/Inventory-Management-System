@extends('layouts.app')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Asset Store Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reports.assets') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label for="from" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
            <input type="text" name="from" id="from" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-white" value="{{ request('from') }}">
        </div>
        <div>
            <label for="to" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
            <input type="text" name="to" id="to" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-white" value="{{ request('to') }}">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filter</button>
        </div>
    </form>

    <!-- Data Summary -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-md p-4 mb-6">
        <p class="mb-2 text-gray-800 dark:text-white"><strong>Total Assets:</strong> {{ $total }}</p>
        <p class="mb-2 text-yellow-600"><strong>Low Stock:</strong> {{ $lowStock }}</p>
        <p class="text-green-600"><strong>In Stock:</strong> {{ $inStock }}</p>
    </div>

    <!-- Low Stock Alerts -->
    @if($byType->isNotEmpty())
        @php $lowStockList = $byType->filter(fn($a) => $a->total_available < $a->threshold); @endphp

        @if($lowStockList->isNotEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md mb-6">
                <strong>Restock Notice:</strong>
                <ul class="list-disc pl-6 mt-2">
                    @foreach($lowStockList as $asset)
                        <li>{{ $asset->name }} is low in stock ({{ $asset->total_available }} remaining)</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    <!-- Asset Counts by Type -->
    @if($byType->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-md p-4 mb-6">
            <h5 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Assets in Store by Type</h5>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">Asset Name</th>
                            <th class="px-4 py-2">Total Quantity</th>
                            <th class="px-4 py-2">Available</th>
                            <th class="px-4 py-2">Dispatched</th>
                            <th class="px-4 py-2">Threshold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byType as $asset)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $asset->name }}</td>
                                <td class="px-4 py-2">{{ $asset->total_quantity }}</td>
                                <td class="px-4 py-2">{{ $asset->total_available }}</td>
                                <td class="px-4 py-2">{{ $asset->dispatched }}</td>
                                <td class="px-4 py-2">{{ $asset->threshold }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @endif

    <!-- Pie Chart -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-md p-4">
        <h5 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Asset Distribution</h5>
        <div class="relative h-[300px] w-full">
            <canvas id="assetChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('assetChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['In Stock', 'Dispatched', 'Low Stock'],
                datasets: [{
                    label: 'Asset Distribution',
                    data: [{{ $inStock }}, {{ $dispatched }}, {{ $lowStock }}],
                    backgroundColor: ['#28a745', '#007bff', '#ffc107'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Initialize Flatpickr
    flatpickr("#from", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

    flatpickr("#to", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>
@endsection


