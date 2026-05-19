@extends('layouts.app')
@section('title', 'Place Order')
@section('page-title', 'Place Order')

@section('content')
<div style="max-width:700px;">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
        <a href="{{ route('orders.sell') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 style="font-size:20px;font-weight:700;">Place Order</h2>
    </div>

    {{-- Crop Summary --}}
    <div class="card" style="margin-bottom:20px;border:1px solid #E8F5E9;">
        <div class="card-body" style="display:flex;align-items:center;gap:20px;padding:20px 24px;">
            <div style="width:72px;height:72px;border-radius:12px;background:#E8F5E9;display:flex;align-items:center;justify-content:center;font-size:32px;color:#2E7D32;flex-shrink:0;">
                @if($crop->image)
                    <img src="{{ asset('storage/'.$crop->image) }}" style="width:72px;height:72px;border-radius:12px;object-fit:cover;" alt="{{ $crop->name }}">
                @else
                    <i class="bi bi-flower2"></i>
                @endif
            </div>
            <div style="flex:1;">
                <div style="font-size:18px;font-weight:700;color:#1A2E1A;">{{ $crop->name }}</div>
                <div style="font-size:13px;color:#9E9E9E;">By {{ $crop->user->name }} · {{ $crop->location }}</div>
                <div style="display:flex;gap:20px;margin-top:10px;flex-wrap:wrap;">
                    <div style="font-size:13px;">
                        <span style="color:#9E9E9E;">Available:</span>
                        <strong>{{ number_format($crop->quantity, 0) }} {{ $crop->unit }}</strong>
                    </div>
                    <div style="font-size:13px;">
                        <span style="color:#9E9E9E;">Price:</span>
                        <strong style="color:#2E7D32;">₹{{ number_format($crop->price_per_unit) }} / {{ $crop->unit }}</strong>
                    </div>
                    <div style="font-size:13px;">
                        <span style="color:#9E9E9E;">Category:</span>
                        <strong>{{ $crop->category }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="bi bi-bag-plus-fill" style="color:#2E7D32;"></i> Order Details</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf
                <input type="hidden" name="crop_id" value="{{ $crop->id }}">
                <input type="hidden" name="price_per_unit" value="{{ $crop->price_per_unit }}" id="pricePerUnit">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Buyer Name *</label>
                        <input type="text" name="buyer_name" class="form-control" placeholder="Full name of buyer" value="{{ old('buyer_name', Auth::user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Buyer Phone *</label>
                        <input type="tel" name="buyer_phone" class="form-control" placeholder="Mobile number" value="{{ old('buyer_phone', Auth::user()->phone) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Buyer Email</label>
                        <input type="email" name="buyer_email" class="form-control" placeholder="Optional" value="{{ old('buyer_email', Auth::user()->email) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="upi">UPI</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity ({{ $crop->unit }}) *</label>
                        <input type="number" name="quantity" id="quantityInput" class="form-control"
                               placeholder="How much to buy" step="0.1" min="0.1"
                               max="{{ $crop->quantity }}" value="{{ old('quantity') }}" required
                               oninput="updateTotal()">
                        <p style="font-size:11px;color:#9E9E9E;margin-top:4px;">Max: {{ number_format($crop->quantity, 0) }} {{ $crop->unit }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}" value="{{ old('delivery_date') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes / Special Instructions</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Any special delivery or payment notes...">{{ old('notes') }}</textarea>
                </div>

                {{-- Order Total --}}
                <div style="background:linear-gradient(135deg,#E8F5E9,#F1F8E9);border-radius:12px;padding:20px 24px;margin:8px 0 20px;border:1px solid #C8E6C9;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:13px;color:#4A4A4A;">Order Total</div>
                            <div style="font-size:12px;color:#9E9E9E;">Quantity × ₹{{ number_format($crop->price_per_unit) }} / {{ $crop->unit }}</div>
                        </div>
                        <div id="totalDisplay" style="font-size:28px;font-weight:800;color:#2E7D32;">₹0</div>
                    </div>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                        <i class="bi bi-bag-check-fill"></i> Confirm Order
                    </button>
                    <a href="{{ route('orders.sell') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTotal() {
    const qty   = parseFloat(document.getElementById('quantityInput').value) || 0;
    const price = parseFloat(document.getElementById('pricePerUnit').value) || 0;
    const total = qty * price;
    document.getElementById('totalDisplay').textContent = '₹' + total.toLocaleString('en-IN', {maximumFractionDigits:2});
}
</script>
@endpush
