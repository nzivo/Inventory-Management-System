@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Asset Store Report</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reports.assets') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="from" class="form-label">From Date</label>
            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label for="to" class="form-label">To Date</label>
            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        {{-- <div class="col-md-3 align-self-end">
            <a href="{{ route('reports.assets.pdf', request()->query()) }}" class="btn btn-success w-100">Export as PDF</a>
        </div> --}}
    </form>

    <!-- Data Summary -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Total Assets:</strong> {{ $total }}</p>
            <p><strong>Dispatched:</strong> {{ $dispatched }}</p>
            <p><strong>In Store:</strong> {{ $inStore }}</p>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    @if($byType->isNotEmpty())
        @php $lowStock = $byType->filter(fn($a) => $a->count < 5); @endphp

        @if($lowStock->isNotEmpty())
            <div class="alert alert-warning mt-4">
                <strong>Restock Notice:</strong>
                <ul class="mb-0">
                    @foreach($lowStock as $asset)
                        <li>{{ $asset->name }} is low in stock ({{ $asset->count }} remaining)</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    <!-- Asset Counts by Type -->
    @if($byType->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Assets in Store by Type</h5>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Quantity in Store</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byType as $asset)
                            <tr>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Pie Chart -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Asset Distribution</h5>
            <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                <canvas id="assetChart"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('assetChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Dispatched', 'In Store'],
            datasets: [{
                label: 'Asset Status',
                data: [{{ $dispatched }}, {{ $inStore }}],
                backgroundColor: ['#dc3545', '#28a745'],
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
</script>
@endsection
