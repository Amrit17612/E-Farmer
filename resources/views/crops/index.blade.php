@extends('layouts.app')
@section('title', 'My Crops')
@section('page-title', 'My Crops')

@push('styles')
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
    .filter-bar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:20px; }
    .crop-image { width:48px; height:48px; border-radius:10px; object-fit:cover; background:#E8F5E9; display:flex; align-items:center; justify-content:center; color:#2E7D32; font-size:22px; }
    .crop-info .name { font-size:14px; font-weight:600; color:#1A2E1A; }
    .crop-info .meta { font-size:12px; color:#9E9E9E; margin-top:2px; }
    .actions-cell { display:flex; gap:8px; }
    .empty-state { text-align:center; padding:80px 40px; color:#9E9E9E; }
    .empty-state i { font-size:64px; color:#C8E6C9; display:block; margin-bottom:16px; }
    .empty-state h3 { font-size:18px; font-weight:600; color:#4A4A4A; margin-bottom:8px; }
    .empty-state p { font-size:14px; margin-bottom:24px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h2 style="font-size:20px;font-weight:700;">My Crops</h2>
        <p style="font-size:13px;color:#757575;">Manage all your crop listings</p>
    </div>
    <a href="{{ route('crops.create') }}" class="btn btn-primary" id="add-crop-btn">
        <i class="bi bi-plus-lg"></i> Add New Crop
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('crops.index') }}">
    <div class="filter-bar">
        <div class="search-bar" style="flex:1;min-width:200px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Search crops..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="width:160px;padding:10px 12px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="status" class="form-control" style="width:140px;padding:10px 12px;">
            <option value="">All Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
            <i class="bi bi-funnel"></i> Filter
        </button>
        @if(request()->hasAny(['search','category','status']))
            <a href="{{ route('crops.index') }}" class="btn btn-outline" style="padding:10px 16px;">Clear</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="card">
    <div class="table-wrapper">
        @if($crops->count())
        <table>
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price / Unit</th>
                    <th>Total Value</th>
                    <th>Harvest Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crops as $crop)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="crop-image">
                                @if($crop->image)
                                    <img src="{{ asset('storage/'.$crop->image) }}" style="width:48px;height:48px;border-radius:10px;object-fit:cover;" alt="{{ $crop->name }}">
                                @else
                                    <i class="bi bi-flower2"></i>
                                @endif
                            </div>
                            <div class="crop-info">
                                <div class="name">{{ $crop->name }}</div>
                                <div class="meta">{{ $crop->location ?? 'No location' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info">{{ $crop->category }}</span></td>
                    <td>{{ number_format($crop->quantity, 1) }} {{ $crop->unit }}</td>
                    <td>₹{{ number_format($crop->price_per_unit) }}</td>
                    <td style="font-weight:600;color:#2E7D32;">₹{{ number_format($crop->total_value) }}</td>
                    <td>{{ $crop->harvest_date ? $crop->harvest_date->format('d M Y') : '—' }}</td>
                    <td>{!! $crop->status_badge !!}</td>
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('crops.edit', $crop) }}" class="btn btn-outline btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('crops.destroy', $crop) }}" onsubmit="return confirm('Delete this crop?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div style="padding:16px 20px;border-top:1px solid #F0F0F0;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:13px;color:#757575;">Showing {{ $crops->firstItem() }}–{{ $crops->lastItem() }} of {{ $crops->total() }} crops</span>
            <div class="pagination">
                @if($crops->onFirstPage())
                    <span class="page-link" style="opacity:.4;">‹ Prev</span>
                @else
                    <a href="{{ $crops->previousPageUrl() }}" class="page-link">‹ Prev</a>
                @endif
                @foreach($crops->getUrlRange(1, $crops->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-link {{ $crops->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($crops->hasMorePages())
                    <a href="{{ $crops->nextPageUrl() }}" class="page-link">Next ›</a>
                @else
                    <span class="page-link" style="opacity:.4;">Next ›</span>
                @endif
            </div>
        </div>

        @else
        <div class="empty-state">
            <i class="bi bi-flower2"></i>
            <h3>No Crops Found</h3>
            <p>{{ request()->hasAny(['search','category','status']) ? 'No crops match your search.' : 'Start by adding your first crop listing.' }}</p>
            <a href="{{ route('crops.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add First Crop
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
