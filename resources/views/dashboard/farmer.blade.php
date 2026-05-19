@extends('layouts.app')
@section('title', 'Farmer Dashboard')
@section('page-title', 'Farmer Dashboard')

@push('styles')
<style>
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px; }
    .charts-grid { display:grid; grid-template-columns:2fr 1fr; gap:24px; margin-bottom:28px; }
    .bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .welcome-bar {
        background:linear-gradient(135deg,#2E7D32,#388E3C,#66BB6A);
        border-radius:14px; padding:28px 32px;
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:28px; color:#fff;
    }
    .welcome-bar h2 { font-size:22px; font-weight:700; margin-bottom:4px; }
    .welcome-bar p { font-size:14px; opacity:.85; }
    .welcome-bar .hero-icon { font-size:56px; opacity:.3; }
    .market-row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #F0F0F0; }
    .market-row:last-child { border:none; }
    .market-row .crop-name { font-weight:600; font-size:14px; }
    .market-row .price { font-size:14px; color:#2E7D32; font-weight:600; }
    .market-row .trend { font-size:12px; font-weight:600; }
    .activity-item { display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid #F5F5F5; }
    .activity-item:last-child { border:none; }
    .activity-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
    .activity-info { flex:1; }
    .activity-info .title { font-size:13px; font-weight:600; color:#1A2E1A; }
    .activity-info .time { font-size:11px; color:#9E9E9E; margin-top:2px; }
    @media(max-width:1100px){ .stats-grid{grid-template-columns:repeat(2,1fr);} .charts-grid{grid-template-columns:1fr;} .bottom-grid{grid-template-columns:1fr;} }
    @media(max-width:600px){ .stats-grid{grid-template-columns:1fr;} .welcome-bar .hero-icon{display:none;} }
</style>
@endpush

@section('content')
<div class="welcome-bar">
    <div>
        <h2>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', $user->name)[0] }} 👋</h2>
        <p>Track crop listings, sales orders, market prices, and farm activity for {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="hero-icon"><i class="bi bi-sun-fill"></i></div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-flower2"></i></div>
        <div class="stat-value" id="total-crops-val">{{ $totalCrops }}</div>
        <div class="stat-label">Total Crops Listed</div>
        <div class="stat-change up" id="active-crops-change"><i class="bi bi-arrow-up-short"></i> {{ $activeCrops }} active</div>
    </div>
    <div class="stat-card" style="--accent:#1565C0;">
        <div class="stat-icon" style="background:#E3F2FD;color:#1565C0;"><i class="bi bi-bag-check-fill"></i></div>
        <div class="stat-value" id="total-orders-val">{{ $totalOrders }}</div>
        <div class="stat-label">Sales Orders</div>
        <div class="stat-change {{ $pendingOrders > 0 ? 'down' : 'up' }}" id="orders-change-val">
            <i class="bi bi-clock"></i> {{ $pendingOrders }} pending
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF8E1;color:#F57F17;"><i class="bi bi-currency-rupee"></i></div>
        <div class="stat-value" id="total-earnings-val">₹{{ number_format($totalEarnings) }}</div>
        <div class="stat-label">Total Earnings</div>
        <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> From completed orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F3E5F5;color:#6A1B9A;"><i class="bi bi-map"></i></div>
        <div class="stat-value">{{ $user->farm_size ?? '—' }}</div>
        <div class="stat-label">Farm Size (acres)</div>
        <div class="stat-change up"><i class="bi bi-geo-alt"></i> {{ $user->location ?? 'Not set' }}</div>
    </div>
</div>

{{-- Charts --}}
<div class="charts-grid">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="bi bi-bar-chart-fill" style="color:#2E7D32;"></i> Monthly Earnings (Last 6 Months)</span>
        </div>
        <div class="card-body" style="height:260px;position:relative;">
            <canvas id="earningsChart"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="bi bi-pie-chart-fill" style="color:#2E7D32;"></i> Crops by Category</span>
        </div>
        <div class="card-body" style="height:260px;position:relative;">
            <canvas id="cropChart"></canvas>
        </div>
    </div>
</div>

{{-- Bottom --}}
<div class="bottom-grid">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Sales Orders</span>
            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($recentOrders as $order)
            <div class="activity-item" style="padding:14px 24px;">
                <div class="activity-dot" style="background:{{ $order->status === 'completed' ? '#2E7D32' : ($order->status === 'cancelled' ? '#ef5350' : '#F57F17') }};"></div>
                <div class="activity-info">
                    <div class="title">{{ $order->crop->name ?? '—' }} — {{ $order->buyer_name }}</div>
                    <div class="time">₹{{ number_format($order->total_amount) }} · {{ $order->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge {{ $order->status === 'completed' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            @empty
            <div style="padding:32px;text-align:center;color:#9E9E9E;font-size:14px;">
                <i class="bi bi-bag-x" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                No orders yet
            </div>
            @endforelse
        </div>
    </div>

    {{-- Market Prices --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Today's Market Prices</span>
            <a href="{{ route('market.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body">
            @foreach($topMarketPrices as $mp)
            <div class="market-row">
                <div>
                    <div class="crop-name">{{ $mp->crop_name }}</div>
                    <div style="font-size:11px;color:#9E9E9E;">{{ $mp->market_name }}</div>
                </div>
                <div class="price">₹{{ number_format($mp->modal_price) }}/{{ $mp->unit }}</div>
                <div class="trend {{ $mp->trend === 'up' ? 'text-success' : ($mp->trend === 'down' ? '' : '') }}"
                     style="color:{{ $mp->trend === 'up' ? '#2E7D32' : ($mp->trend === 'down' ? '#c62828' : '#F57F17') }}">
                    {{ $mp->trend_icon }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const earningsData = @json($monthlyEarnings);
const cropData = @json($cropCategories);
const colors = ['#2E7D32','#66BB6A','#A5D6A7','#388E3C','#81C784','#C8E6C9','#1B5E20'];

// Earnings Chart
new Chart(document.getElementById('earningsChart'), {
    type: 'bar',
    data: {
        labels: earningsData.map(d => d.label),
        datasets: [{
            label: 'Earnings (₹)',
            data: earningsData.map(d => d.total),
            backgroundColor: 'rgba(46,125,50,0.15)',
            borderColor: '#2E7D32',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F0F0F0' }, ticks: { font: { family: 'Poppins', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 } } }
        }
    }
});

// Crop Doughnut
const cropLabels = Object.keys(cropData);
const cropValues = Object.values(cropData);
if (cropLabels.length > 0) {
    new Chart(document.getElementById('cropChart'), {
        type: 'doughnut',
        data: {
            labels: cropLabels,
            datasets: [{ data: cropValues, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 11 }, padding: 12 } } }
        }
    });
} else {
    document.getElementById('cropChart').parentElement.innerHTML =
        '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9E9E9E;font-size:14px;">No crops added yet</div>';
}

// Real-time polling
document.addEventListener('DOMContentLoaded', function() {
    const updateStats = async () => {
        try {
            const response = await fetch("{{ route('dashboard.stats') }}");
            if (response.ok) {
                const data = await response.json();
                
                const animateUpdate = (el, newVal) => {
                    if (el && el.innerText != newVal) {
                        el.innerText = newVal;
                        el.classList.add('pop-animation');
                        setTimeout(() => el.classList.remove('pop-animation'), 400);
                    }
                };

                animateUpdate(document.getElementById('total-crops-val'), data.totalCrops);
                
                const activeEl = document.getElementById('active-crops-change');
                if (activeEl) {
                    const activeText = `${data.activeCrops} active`;
                    if (activeEl.innerText != activeText) {
                        activeEl.innerHTML = `<i class="bi bi-arrow-up-short"></i> ${activeText}`;
                    }
                }

                animateUpdate(document.getElementById('total-orders-val'), data.totalOrders);
                
                const pendingEl = document.getElementById('orders-change-val');
                if (pendingEl) {
                    const pendingText = `${data.pendingOrders} pending`;
                    if (pendingEl.innerText != pendingText) {
                        pendingEl.innerHTML = `<i class="bi bi-clock"></i> ${pendingText}`;
                    }
                }
                
                const earningsStr = '₹' + Number(data.totalEarnings).toLocaleString('en-IN');
                animateUpdate(document.getElementById('total-earnings-val'), earningsStr);
            }
        } catch (err) {
            console.error("Stats poll failed:", err);
        }
    };

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes statPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.12); color: #2E7D32; }
            100% { transform: scale(1); }
        }
        .pop-animation {
            animation: statPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-block;
        }
    `;
    document.head.appendChild(style);

    setInterval(updateStats, 4000);
});
</script>
@endpush
