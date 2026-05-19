@extends('layouts.app')
@section('title', 'Buyer Dashboard')
@section('page-title', 'Buyer Dashboard')

@push('styles')
<style>
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px; }
    .dashboard-grid { display:grid; grid-template-columns:1.2fr .8fr; gap:24px; margin-bottom:28px; }
    .crop-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
    .mini-crop { border:1px solid #E8F5E9; border-radius:12px; padding:16px; background:#fff; }
    .mini-crop .name { font-weight:700; color:#1A2E1A; margin-bottom:4px; }
    .mini-crop .meta { font-size:12px; color:#757575; margin-bottom:10px; }
    .mini-crop .price { font-size:18px; font-weight:800; color:#2E7D32; }
    .activity-item { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid #F5F5F5; }
    .activity-item:last-child { border:none; }
    .activity-info { flex:1; }
    .activity-info .title { font-size:13px; font-weight:600; color:#1A2E1A; }
    .activity-info .time { font-size:11px; color:#9E9E9E; margin-top:2px; }
    .welcome-bar { background:linear-gradient(135deg,#1565C0,#2E7D32); border-radius:14px; padding:28px 32px; display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; color:#fff; }
    .welcome-bar h2 { font-size:22px; font-weight:700; margin-bottom:4px; }
    .welcome-bar p { font-size:14px; opacity:.86; }
    .welcome-bar .hero-icon { font-size:56px; opacity:.3; }
    @media(max-width:1100px){ .stats-grid{grid-template-columns:repeat(2,1fr);} .dashboard-grid{grid-template-columns:1fr;} }
    @media(max-width:600px){ .stats-grid{grid-template-columns:1fr;} .welcome-bar .hero-icon{display:none;} }
</style>
@endpush

@section('content')
<div class="welcome-bar">
    <div>
        <h2>Welcome, {{ explode(' ', $user->name)[0] }}</h2>
        <p>Browse fresh crop listings, track purchases, and compare current mandi prices.</p>
    </div>
    <div class="hero-icon"><i class="bi bi-basket2-fill"></i></div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-shop"></i></div>
        <div class="stat-value" id="available-crops-val">{{ $availableCrops }}</div>
        <div class="stat-label">Available Crops</div>
        <div class="stat-change up"><i class="bi bi-search"></i> Ready to buy</div>
    </div>
    <div class="stat-card" style="--accent:#1565C0;">
        <div class="stat-icon" style="background:#E3F2FD;color:#1565C0;"><i class="bi bi-bag-check-fill"></i></div>
        <div class="stat-value" id="total-purchases-val">{{ $totalPurchases }}</div>
        <div class="stat-label">My Purchases</div>
        <div class="stat-change {{ $pendingPurchases > 0 ? 'down' : 'up' }}" id="pending-purchases-change"><i class="bi bi-clock"></i> {{ $pendingPurchases }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF8E1;color:#F57F17;"><i class="bi bi-currency-rupee"></i></div>
        <div class="stat-value" id="total-spent-val">₹{{ number_format($totalSpent) }}</div>
        <div class="stat-label">Total Spent</div>
        <div class="stat-change up"><i class="bi bi-receipt"></i> Confirmed and completed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F3E5F5;color:#6A1B9A;"><i class="bi bi-geo-alt-fill"></i></div>
        <div class="stat-value">{{ $user->location ? Str::limit($user->location, 10) : 'Any' }}</div>
        <div class="stat-label">Buying Region</div>
        <div class="stat-change up"><i class="bi bi-sliders"></i> Update in profile</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="bi bi-basket2-fill" style="color:#2E7D32;"></i> Fresh Listings</span>
            <a href="{{ route('orders.sell') }}" class="btn btn-outline btn-sm">Browse All</a>
        </div>
        <div class="card-body">
            <div class="crop-list">
                @forelse($featuredCrops as $crop)
                    <div class="mini-crop">
                        <div class="name">{{ $crop->name }}</div>
                        <div class="meta">{{ $crop->quantity }} {{ $crop->unit }} from {{ $crop->user->name }}</div>
                        <div class="price">₹{{ number_format($crop->price_per_unit) }} <span style="font-size:12px;color:#9E9E9E;font-weight:500;">/ {{ $crop->unit }}</span></div>
                        <a href="{{ route('orders.create', ['crop_id' => $crop->id]) }}" class="btn btn-primary btn-sm" style="margin-top:12px;width:100%;justify-content:center;">Buy</a>
                    </div>
                @empty
                    <div style="color:#9E9E9E;font-size:14px;">No crops are available right now.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Purchases</span>
            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body">
            @forelse($recentPurchases as $order)
                <div class="activity-item">
                    <div class="activity-info">
                        <div class="title">{{ $order->crop->name ?? 'Crop' }} from {{ $order->crop->user->name ?? 'farmer' }}</div>
                        <div class="time">₹{{ number_format($order->total_amount) }} · {{ $order->created_at->diffForHumans() }}</div>
                    </div>
                    {!! $order->status_badge !!}
                </div>
            @empty
                <div style="text-align:center;color:#9E9E9E;font-size:14px;padding:24px;">
                    <i class="bi bi-bag" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                    No purchases yet
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Market Watch</span>
        <a href="{{ route('market.index') }}" class="btn btn-outline btn-sm">View Market</a>
    </div>
    <div class="card-body">
        <div class="crop-list">
            @foreach($topMarketPrices as $price)
                <div class="mini-crop">
                    <div class="name">{{ $price->crop_name }}</div>
                    <div class="meta">{{ $price->market_name }} · {{ $price->state }}</div>
                    <div class="price">₹{{ number_format($price->modal_price) }} <span style="font-size:12px;color:#9E9E9E;font-weight:500;">/ {{ $price->unit }}</span></div>
                </div>
            @endforeach
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

                    animateUpdate(document.getElementById('available-crops-val'), data.availableCrops);
                    animateUpdate(document.getElementById('total-purchases-val'), data.totalPurchases);
                    
                    const pendingEl = document.getElementById('pending-purchases-change');
                    if (pendingEl) {
                        const pendingText = `${data.pendingPurchases} pending`;
                        if (pendingEl.innerText != pendingText) {
                            pendingEl.innerHTML = `<i class="bi bi-clock"></i> ${pendingText}`;
                        }
                    }
                    
                    const spentStr = '₹' + Number(data.totalSpent).toLocaleString('en-IN');
                    animateUpdate(document.getElementById('total-spent-val'), spentStr);
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
