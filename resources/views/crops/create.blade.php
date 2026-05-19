@extends('layouts.app')
@section('title', 'Add New Crop')
@section('page-title', 'Add New Crop')

@section('content')
<div style="max-width:800px;">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
        <a href="{{ route('crops.index') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 style="font-size:20px;font-weight:700;">Add New Crop</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('crops.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Crop Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Wheat, Rice, Tomato" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" placeholder="e.g. 500" step="0.1" min="0" value="{{ old('quantity') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit *</label>
                        <select name="unit" class="form-control" required>
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price per Unit (₹) *</label>
                        <input type="number" name="price_per_unit" class="form-control" placeholder="e.g. 2200" step="0.01" min="0" value="{{ old('price_per_unit') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harvest Date</label>
                        <input type="date" name="harvest_date" class="form-control" value="{{ old('harvest_date') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Ludhiana, Punjab" value="{{ old('location') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Describe your crop quality, variety, etc.">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Crop Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" id="cropImageInput">
                    <div id="imagePreview" style="display:none;margin-top:12px;">
                        <img id="previewImg" src="" alt="" style="width:120px;height:90px;border-radius:10px;object-fit:cover;border:2px solid #E8F5E9;">
                    </div>
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="submit-crop-btn">
                        <i class="bi bi-plus-circle"></i> Add Crop
                    </button>
                    <a href="{{ route('crops.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('cropImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
