@extends('layouts.app')
@section('title', 'Edit Crop')
@section('page-title', 'Edit Crop')

@section('content')
<div style="max-width:800px;">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
        <a href="{{ route('crops.index') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 style="font-size:20px;font-weight:700;">Edit: {{ $crop->name }}</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('crops.update', $crop) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Crop Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $crop->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $crop->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" step="0.1" min="0" value="{{ old('quantity', $crop->quantity) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit *</label>
                        <select name="unit" class="form-control" required>
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ old('unit', $crop->unit) === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price per Unit (₹) *</label>
                        <input type="number" name="price_per_unit" class="form-control" step="0.01" min="0" value="{{ old('price_per_unit', $crop->price_per_unit) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harvest Date</label>
                        <input type="date" name="harvest_date" class="form-control" value="{{ old('harvest_date', $crop->harvest_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $crop->location) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status', $crop->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $crop->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Update Image</label>
                    @if($crop->image)
                        <div style="margin-bottom:12px;">
                            <img src="{{ asset('storage/'.$crop->image) }}" style="width:100px;height:80px;border-radius:10px;object-fit:cover;" alt="{{ $crop->name }}">
                            <p style="font-size:12px;color:#9E9E9E;margin-top:4px;">Current image</p>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Crop
                    </button>
                    <a href="{{ route('crops.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
