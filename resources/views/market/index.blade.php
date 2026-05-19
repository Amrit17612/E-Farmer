@extends('layouts.app')
@section('title', 'Market Prices')
@section('page-title', 'Market Prices')

@push('styles')
<style>
    .price-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:16px; }
    .filter-wrap { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px; }
    .trend-up   { color:#2E7D32; font-weight:700; }
    .trend-down { color:#c62828; font-weight:700; }
    .trend-stable { color:#F57F17; font-weight:700; }
    .price-badge { font-size:18px; font-weight:700; color:#1A2E1A; }
    .market-label { font-size:11px; color:#9E9E9E; margin-top:2px; }
    .summary-strip { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .strip-card { background:#fff; border-radius:12px; padding:20px 24px; border:1px solid #E8F5E9; box-shadow:0 2px 8px rgba(46,125,50,.06); }
    .strip-card .val { font-size:22px; font-weight:700; color:#2E7D32; }
    .strip-card .lbl { font-size:12px; color:#9E9E9E; margin-top:4px; }
    @media(max-width:700px){ .summary-strip{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="price-header">
    <div>
        <h2 style="font-size:20px;font-weight:700;">Live Market Prices</h2>
        <p style="font-size:13px;color:#757575;">Real-time crop prices from mandis across India</p>
    </div>
    <div style="font-size:12px;color:#9E9E9E;background:#E8F5E9;padding:8px 16px;border-radius:8px;">
        <i class="bi bi-clock" style="color:#2E7D32;"></i> Updated: {{ now()->format('d M Y, h:i A') }}
    </div>
</div>

{{-- Summary --}}
<div class="summary-strip">
    <div class="strip-card">
        <div class="val">{{ $prices->total() }}</div>
        <div class="lbl">Total Price Records</div>
    </div>
    <div class="strip-card">
        <div class="val">{{ $states->count() }}</div>
        <div class="lbl">States Covered</div>
    </div>
    <div class="strip-card">
        <div class="val">{{ count($categories) }}</div>
        <div class="lbl">Crop Categories</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('market.index') }}">
    <div class="filter-wrap">
        <div class="search-bar" style="flex:1;min-width:220px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Search crop or market..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="width:160px;padding:10px 12px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="state" class="form-control" style="width:150px;padding:10px 12px;">
            <option value="">All States</option>
            @foreach($states as $state)
                <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
            @endforeach
        </select>
        <select name="trend" class="form-control" style="width:130px;padding:10px 12px;">
            <option value="">All Trends</option>
            <option value="up"     {{ request('trend') === 'up'     ? 'selected' : '' }}>↑ Rising</option>
            <option value="down"   {{ request('trend') === 'down'   ? 'selected' : '' }}>↓ Falling</option>
            <option value="stable" {{ request('trend') === 'stable' ? 'selected' : '' }}>→ Stable</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
            <i class="bi bi-funnel"></i> Filter
        </button>
        @if(request()->hasAny(['search','category','state','trend']))
            <a href="{{ route('market.index') }}" class="btn btn-outline" style="padding:10px 16px;">Clear</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="card">
    <div class="table-wrapper">
        @if($prices->count())
        <table>
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Category</th>
                    <th>Market / Mandi</th>
                    <th>State</th>
                    <th>Min Price (₹)</th>
                    <th>Max Price (₹)</th>
                    <th>Modal Price (₹)</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                <tr>
                    <td><strong>{{ $price->crop_name }}</strong></td>
                    <td><span class="badge badge-info">{{ $price->category }}</span></td>
                    <td>
                        <div style="font-size:13px;font-weight:500;">{{ $price->market_name }}</div>
                        <div class="market-label">{{ $price->district }}</div>
                    </td>
                    <td>{{ $price->state }}</td>
                    <td>{{ number_format($price->min_price) }}</td>
                    <td>{{ number_format($price->max_price) }}</td>
                    <td class="price-badge">{{ number_format($price->modal_price) }}</td>
                    <td>{{ $price->unit }}</td>
                    <td>{{ $price->price_date->format('d M Y') }}</td>
                    <td class="trend-{{ $price->trend }}">{{ $price->trend_icon }} {{ ucfirst($price->trend) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:16px 20px;border-top:1px solid #F0F0F0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <span style="font-size:13px;color:#757575;">Showing {{ $prices->firstItem() }}–{{ $prices->lastItem() }} of {{ $prices->total() }}</span>
            <div class="pagination">
                @if(!$prices->onFirstPage())
                    <a href="{{ $prices->previousPageUrl() }}" class="page-link">‹</a>
                @endif
                @foreach($prices->getUrlRange(max(1,$prices->currentPage()-2), min($prices->lastPage(),$prices->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}" class="page-link {{ $prices->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($prices->hasMorePages())
                    <a href="{{ $prices->nextPageUrl() }}" class="page-link">›</a>
                @endif
            </div>
        </div>
        @else
        <div style="text-align:center;padding:60px;color:#9E9E9E;">
            <i class="bi bi-search" style="font-size:48px;color:#C8E6C9;display:block;margin-bottom:12px;"></i>
            <p>No market prices found for your search.</p>
        </div>
        @endif
    </div>
</div>
@endsection
