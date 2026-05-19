@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@push('styles')
<style>
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px; }
    .admin-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .welcome-bar { background:linear-gradient(135deg,#263238,#2E7D32); border-radius:14px; padding:28px 32px; display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; color:#fff; }
    .welcome-bar h2 { font-size:22px; font-weight:700; margin-bottom:4px; }
    .welcome-bar p { font-size:14px; opacity:.86; }
    .welcome-bar .hero-icon { font-size:56px; opacity:.3; }
    .activity-item { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid #F5F5F5; }
    .activity-item:last-child { border:none; }
    .activity-info { flex:1; }
    .activity-info .title { font-size:13px; font-weight:600; color:#1A2E1A; }
    .activity-info .time { font-size:11px; color:#9E9E9E; margin-top:2px; }
    @media(max-width:1100px){ .stats-grid{grid-template-columns:repeat(2,1fr);} .admin-grid{grid-template-columns:1fr;} }
    @media(max-width:600px){ .stats-grid{grid-template-columns:1fr;} .welcome-bar .hero-icon{display:none;} }
</style>
@endpush

@section('content')
<div class="welcome-bar">
    <div>
        <h2>Admin Overview</h2>
        <p>Monitor users, crop listings, orders, schemes, and platform activity.</p>
    </div>
    <div class="hero-icon"><i class="bi bi-speedometer2"></i></div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div class="stat-value" id="total-users-val">{{ $totalUsers }}</div>
        <div class="stat-label">Total Users</div>
        <div class="stat-change up" id="users-change-val">{{ $totalFarmers }} farmers · {{ $totalBuyers }} buyers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#E8F5E9;color:#2E7D32;"><i class="bi bi-flower2"></i></div>
        <div class="stat-value" id="total-crops-val">{{ $totalCrops }}</div>
        <div class="stat-label">Crop Listings</div>
        <div class="stat-change up">Across all farmers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#E3F2FD;color:#1565C0;"><i class="bi bi-bag-check-fill"></i></div>
        <div class="stat-value" id="total-orders-val">{{ $totalOrders }}</div>
        <div class="stat-label">Orders</div>
        <div class="stat-change {{ $pendingOrders > 0 ? 'down' : 'up' }}" id="orders-change-val">{{ $pendingOrders }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF8E1;color:#F57F17;"><i class="bi bi-currency-rupee"></i></div>
        <div class="stat-value" id="total-revenue-val">₹{{ number_format($totalRevenue) }}</div>
        <div class="stat-label">Completed Order Value</div>
        <div class="stat-change up">{{ $activeSchemes }} active schemes</div>
    </div>
</div>

<div class="admin-grid">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Users</span>
        </div>
        <div class="card-body">
            @foreach($latestUsers as $member)
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="title">{{ $member->name }}</div>
                        <div class="time">{{ $member->email }} · joined {{ $member->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge {{ $member->isAdmin() ? 'badge-primary' : ($member->isBuyer() ? 'badge-info' : 'badge-success') }}">{{ ucfirst($member->role) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Orders</span>
        </div>
        <div class="card-body">
            @forelse($recentOrders as $order)
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="title">#{{ $order->id }} · {{ $order->crop->name ?? 'Crop' }}</div>
                        <div class="time">Seller: {{ $order->crop->user->name ?? 'Unknown' }} · ₹{{ number_format($order->total_amount) }}</div>
                    </div>
                    {!! $order->status_badge !!}
                </div>
            @empty
                <div style="text-align:center;color:#9E9E9E;font-size:14px;padding:24px;">No orders yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

                    animateUpdate(document.getElementById('total-users-val'), data.totalUsers);
                    animateUpdate(document.getElementById('users-change-val'), `${data.totalFarmers} farmers · ${data.totalBuyers} buyers`);
                    animateUpdate(document.getElementById('total-crops-val'), data.totalCrops);
                    animateUpdate(document.getElementById('total-orders-val'), data.totalOrders);
                    
                    const pendingEl = document.getElementById('orders-change-val');
                    if (pendingEl) {
                        animateUpdate(pendingEl, `${data.pendingOrders} pending`);
                        if (data.pendingOrders > 0) {
                            pendingEl.className = 'stat-change down';
                        } else {
                            pendingEl.className = 'stat-change up';
                        }
                    }
                    
                    const revenueStr = '₹' + Number(data.totalRevenue).toLocaleString('en-IN');
                    animateUpdate(document.getElementById('total-revenue-val'), revenueStr);
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

        // Poll every 4 seconds for super snappy real-time feedback
        setInterval(updateStats, 4000);
    });
</script>
@endpush
