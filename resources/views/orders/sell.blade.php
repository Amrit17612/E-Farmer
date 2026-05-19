@extends('layouts.app')
@section('title', 'Browse Crops')
@section('page-title', 'Browse Crops')

@push('styles')
<style>
    .sell-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
    .crops-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:24px; }
    .crop-card {
        background:#fff; border-radius:16px;
        box-shadow:0 4px 20px rgba(46,125,50,0.07);
        border:1px solid #E8F5E9; overflow:hidden; transition:.3s;
    }
    .crop-card:hover { transform:translateY(-4px); box-shadow:0 10px 36px rgba(46,125,50,0.13); }
    .crop-card-img {
        height:160px; background:linear-gradient(135deg,#E8F5E9,#A5D6A7);
        display:flex; align-items:center; justify-content:center;
        font-size:56px; color:#2E7D32; position:relative; overflow:hidden;
    }
    .crop-card-img img { width:100%; height:100%; object-fit:cover; }
    .crop-card-img .cat-badge {
        position:absolute; top:12px; right:12px;
        background:rgba(255,255,255,0.92); color:#2E7D32;
        padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600;
    }
    .crop-card-body { padding:20px; }
    .crop-card-body .crop-name { font-size:17px; font-weight:700; color:#1A2E1A; margin-bottom:4px; }
    .crop-card-body .farmer-name { font-size:12px; color:#9E9E9E; margin-bottom:12px; }
    .crop-details { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px; }
    .crop-detail { background:#F9FBF9; border-radius:8px; padding:8px 10px; }
    .crop-detail .d-label { font-size:10px; color:#9E9E9E; margin-bottom:2px; }
    .crop-detail .d-value { font-size:13px; font-weight:600; color:#1A2E1A; }
    .crop-price { font-size:20px; font-weight:800; color:#2E7D32; margin-bottom:14px; }
    .crop-price span { font-size:13px; color:#9E9E9E; font-weight:400; }
    .empty-state { text-align:center; padding:80px; color:#9E9E9E; grid-column:1/-1; }
    .empty-state i { font-size:64px; color:#C8E6C9; display:block; margin-bottom:16px; }
</style>
@endpush

@section('content')
<div class="sell-head">
    <div>
        <h2 style="font-size:20px;font-weight:700;">Browse Crops</h2>
        <p style="font-size:13px;color:#757575;">Buy active crop listings from farmers across India</p>
    </div>
    @if(Auth::user()->isFarmer())
    <a href="{{ route('crops.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> List My Crop
    </a>
    @endif
</div>

<div class="crops-grid">
    @forelse($crops as $crop)
    <div class="crop-card">
        <div class="crop-card-img">
            @if($crop->image)
                <img src="{{ asset('storage/'.$crop->image) }}" alt="{{ $crop->name }}">
            @else
                <i class="bi bi-flower2"></i>
            @endif
            <div class="cat-badge">{{ $crop->category }}</div>
        </div>
        <div class="crop-card-body">
            <div class="crop-name">{{ $crop->name }}</div>
            <div class="farmer-name">
                <i class="bi bi-person-circle"></i> {{ $crop->user->name }}
                @if($crop->location)
                    · <i class="bi bi-geo-alt"></i> {{ $crop->location }}
                @endif
            </div>
            <div class="crop-details">
                <div class="crop-detail">
                    <div class="d-label">Quantity</div>
                    <div class="d-value">{{ number_format($crop->quantity, 0) }} {{ $crop->unit }}</div>
                </div>
                <div class="crop-detail">
                    <div class="d-label">Harvest</div>
                    <div class="d-value">{{ $crop->harvest_date ? $crop->harvest_date->format('d M Y') : 'Ready' }}</div>
                </div>
            </div>
            <div class="crop-price">₹{{ number_format($crop->price_per_unit) }} <span>/ {{ $crop->unit }}</span></div>
            @if(Auth::id() !== $crop->user_id)
                <a href="{{ route('orders.create', ['crop_id' => $crop->id]) }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <i class="bi bi-bag-plus-fill"></i> Buy Now
                </a>
            @else
                <span class="badge badge-secondary" style="justify-content:center;width:100%;padding:10px;">Your listing</span>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-shop"></i>
        <h3 style="font-size:18px;font-weight:600;color:#4A4A4A;margin-bottom:8px;">No Crops Listed</h3>
        <p>Be the first farmer to list your crop for sale!</p>
        <a href="{{ route('crops.create') }}" class="btn btn-primary" style="margin-top:16px;">
            <i class="bi bi-plus-lg"></i> List My Crop
        </a>
    </div>
    @endforelse
</div>

@if($crops->hasPages())
<div style="display:flex;justify-content:center;margin-top:32px;">
    <div class="pagination">
        @if(!$crops->onFirstPage())
            <a href="{{ $crops->previousPageUrl() }}" class="page-link">‹ Previous</a>
        @endif
        @foreach($crops->getUrlRange(1, $crops->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $crops->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($crops->hasMorePages())
            <a href="{{ $crops->nextPageUrl() }}" class="page-link">Next ›</a>
        @endif
    </div>
</div>
@endif
@endsection
