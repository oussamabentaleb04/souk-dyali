@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
<h2>Admin dashboard 👑</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Users</div><div class="fs-4">{{ $usersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Approved sellers</div><div class="fs-4">{{ $sellersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Pending applications</div><div class="fs-4">{{ $pendingApplications }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Products</div><div class="fs-4">{{ $productsCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Orders</div><div class="fs-4">{{ $ordersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Revenue</div><div class="fs-4">{{ number_format($revenue, 0) }} DH</div>
    </div></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h6>Orders per day (last 14 days)</h6>
            <canvas id="chartDays" height="180"></canvas>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h6>Revenue by category (DH)</h6>
            <canvas id="chartCategories" height="180"></canvas>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const textColor = '#adb5bd';
    const gridColor = 'rgba(255,255,255,0.1)';

    new Chart(document.getElementById('chartDays'), {
        type: 'line',
        data: {
            labels: @json($chartLabelsDays),
            datasets: [{ label: 'Orders', data: @json($chartOrdersPerDay), borderColor: '#0dcaf0', tension: 0.3 }]
        },
        options: { scales: { x: { ticks: { color: textColor }, grid: { color: gridColor } }, y: { ticks: { color: textColor }, grid: { color: gridColor }, beginAtZero: true } }, plugins: { legend: { labels: { color: textColor } } } }
    });

    new Chart(document.getElementById('chartCategories'), {
        type: 'doughnut',
        data: {
            labels: @json($chartCategoryLabels),
            datasets: [{ data: @json($chartCategoryValues), backgroundColor: ['#0d6efd','#0dcaf0','#ffc107','#dc3545','#20c997','#6f42c1','#fd7e14','#20c997','#e83e8c'] }]
        },
        options: { plugins: { legend: { labels: { color: textColor } } } }
    });
</script>
@endpush