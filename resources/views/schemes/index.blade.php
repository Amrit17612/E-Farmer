@extends('layouts.app')
@section('title', 'Government Schemes')
@section('page-title', 'Government Schemes')

@push('styles')
<style>
    .schemes-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:16px; }
    .scheme-filters { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px; }
    .scheme-filter-btn {
        padding:8px 18px; border-radius:20px; border:1.5px solid #E0E0E0;
        background:#fff; font-family:'Poppins',sans-serif; font-size:13px; font-weight:500;
        color:#757575; cursor:pointer; transition:.3s; text-decoration:none;
    }
    .scheme-filter-btn:hover, .scheme-filter-btn.active {
        background:#2E7D32; color:#fff; border-color:#2E7D32;
    }
    .schemes-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:24px; }
    .scheme-card {
        background:#fff; border-radius:16px; border:1px solid #E8F5E9;
        box-shadow:0 4px 20px rgba(46,125,50,.07); overflow:hidden; transition:.3s;
        display:flex; flex-direction:column;
    }
    .scheme-card:hover { transform:translateY(-4px); box-shadow:0 10px 36px rgba(46,125,50,.13); }
    .scheme-card-top {
        background:linear-gradient(135deg,#2E7D32,#388E3C);
        padding:24px 20px; color:#fff; position:relative; overflow:hidden;
    }
    .scheme-card-top::after {
        content:''; position:absolute; right:-20px; top:-20px;
        width:100px; height:100px; border-radius:50%;
        background:rgba(255,255,255,0.08);
    }
    .scheme-card-top .scheme-cat-badge {
        display:inline-block; background:rgba(255,255,255,0.2);
        padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600;
        margin-bottom:10px; backdrop-filter:blur(10px);
    }
    .scheme-card-top h3 { font-size:16px; font-weight:700; line-height:1.4; }
    .scheme-card-top .ministry { font-size:12px; opacity:.75; margin-top:6px; }
    .scheme-card-body { padding:20px; flex:1; }
    .scheme-card-body p { font-size:13px; color:#4A4A4A; line-height:1.7; margin-bottom:16px; }
    .scheme-meta { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
    .scheme-meta-item { display:flex; align-items:center; gap:6px; font-size:12px; color:#757575; }
    .scheme-benefits { background:#F9FBF9; border-radius:10px; padding:12px 14px; font-size:12px; color:#2E7D32; font-weight:500; line-height:1.6; }
    .scheme-card-footer { padding:16px 20px; border-top:1px solid #F0F0F0; display:flex; gap:10px; align-items:center; }
</style>
@endpush

@section('content')
<div class="schemes-header">
    <div>
        <h2 style="font-size:20px;font-weight:700;">Government Schemes</h2>
        <p style="font-size:13px;color:#757575;">Discover and apply for agriculture schemes & subsidies</p>
    </div>
    <div style="background:#E8F5E9;color:#2E7D32;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;">
        <i class="bi bi-shield-check"></i> {{ $schemes->total() }} Active Schemes Available
    </div>
</div>

{{-- Category Filters --}}
<form method="GET" action="{{ route('schemes.index') }}" style="margin-bottom:16px;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
        <div class="search-bar" style="flex:1;min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Search schemes..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;">Search</button>
        @if(request('search')) <a href="{{ route('schemes.index') }}" class="btn btn-outline" style="padding:10px 16px;">Clear</a> @endif
    </div>
</form>

<div class="scheme-filters">
    <a href="{{ route('schemes.index') }}" class="scheme-filter-btn {{ !request('category') ? 'active' : '' }}">All</a>
    @foreach($categories as $cat)
        <a href="{{ route('schemes.index', ['category' => $cat]) }}" class="scheme-filter-btn {{ request('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
    @endforeach
</div>

{{-- Scheme Cards --}}
<div class="schemes-grid">
    @forelse($schemes as $scheme)
    <div class="scheme-card">
        <div class="scheme-card-top">
            <div class="scheme-cat-badge"><i class="bi bi-tag"></i> {{ $scheme->category }}</div>
            <h3>{{ $scheme->title }}</h3>
            <div class="ministry"><i class="bi bi-building"></i> {{ $scheme->ministry }}</div>
        </div>
        <div class="scheme-card-body">
            <p>{{ Str::limit($scheme->description, 120) }}</p>
            <div class="scheme-meta">
                @if($scheme->deadline)
                <div class="scheme-meta-item">
                    <i class="bi bi-calendar-event"></i>
                    Deadline: {{ $scheme->deadline->format('d M Y') }}
                    @if($scheme->is_expired) <span style="color:#c62828;">(Expired)</span> @endif
                </div>
                @endif
            </div>
            @if($scheme->benefits)
            <div class="scheme-benefits">
                <i class="bi bi-gift-fill"></i> {{ Str::limit($scheme->benefits, 100) }}
            </div>
            @endif
        </div>
        <div class="scheme-card-footer">
            <a href="{{ route('schemes.show', $scheme) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">
                <i class="bi bi-info-circle"></i> View Details
            </a>
            <form method="POST" action="{{ route('schemes.apply', $scheme) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm" {{ $scheme->is_expired ? 'disabled' : '' }}>
                    <i class="bi bi-send-fill"></i> Apply Now
                </button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:80px;color:#9E9E9E;">
        <i class="bi bi-bank2" style="font-size:56px;color:#C8E6C9;display:block;margin-bottom:12px;"></i>
        <p>No schemes found matching your criteria.</p>
    </div>
    @endforelse
</div>

@if($schemes->hasPages())
<div class="pagination" style="justify-content:center;margin-top:32px;">
    @if(!$schemes->onFirstPage()) <a href="{{ $schemes->previousPageUrl() }}" class="page-link">‹</a> @endif
    @foreach($schemes->getUrlRange(1,$schemes->lastPage()) as $p=>$u)
        <a href="{{ $u }}" class="page-link {{ $schemes->currentPage()===$p?'active':'' }}">{{ $p }}</a>
    @endforeach
    @if($schemes->hasMorePages()) <a href="{{ $schemes->nextPageUrl() }}" class="page-link">›</a> @endif
</div>
@endif
@endsection
