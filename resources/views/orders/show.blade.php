@extends('layouts.app')
@section('title', 'Order #' . $order->id)
@section('page-title', 'Order Details')

@section('content')
<div style="max-width:780px;">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
        <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        <h2 style="font-size:20px;font-weight:700;">Order #{{ $order->id }}</h2>
        {!! $order->status_badge !!}
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
        {{-- Crop Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-flower2" style="color:#2E7D32;"></i> Crop Details</span></div>
            <div class="card-body">
                <div style="display:flex;gap:14px;align-items:center;margin-bottom:16px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:#E8F5E9;display:flex;align-items:center;justify-content:center;font-size:24px;color:#2E7D32;">
                        <i class="bi bi-flower2"></i>
                    </div>
                    <div>
                        <div style="font-size:16px;font-weight:700;">{{ $order->crop->name ?? '—' }}</div>
                        <div style="font-size:12px;color:#9E9E9E;">{{ $order->crop->category ?? '' }}</div>
                    </div>
                </div>
                <table style="width:100%;font-size:13px;">
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Quantity</td><td style="font-weight:600;text-align:right;">{{ number_format($order->quantity,1) }} {{ $order->crop->unit ?? '' }}</td></tr>
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Price/Unit</td><td style="font-weight:600;text-align:right;">₹{{ number_format($order->price_per_unit) }}</td></tr>
                    <tr style="border-top:2px solid #E8F5E9;"><td style="color:#1A2E1A;font-weight:700;padding:10px 0;">Total Amount</td><td style="font-size:20px;font-weight:800;color:#2E7D32;text-align:right;">₹{{ number_format($order->total_amount) }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Buyer Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-person-circle" style="color:#2E7D32;"></i> Buyer Details</span></div>
            <div class="card-body">
                <table style="width:100%;font-size:13px;">
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Name</td><td style="font-weight:600;text-align:right;">{{ $order->buyer_name }}</td></tr>
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Phone</td><td style="font-weight:600;text-align:right;">{{ $order->buyer_phone }}</td></tr>
                    @if($order->buyer_email)
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Email</td><td style="font-weight:600;text-align:right;">{{ $order->buyer_email }}</td></tr>
                    @endif
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Payment</td><td style="text-align:right;"><span class="badge badge-info">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></td></tr>
                    @if($order->delivery_date)
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Delivery</td><td style="font-weight:600;text-align:right;">{{ $order->delivery_date->format('d M Y') }}</td></tr>
                    @endif
                    <tr><td style="color:#9E9E9E;padding:6px 0;">Ordered</td><td style="font-weight:600;text-align:right;">{{ $order->created_at->format('d M Y, h:i A') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    @if($order->notes)
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <strong style="font-size:13px;"><i class="bi bi-chat-text"></i> Notes:</strong>
            <p style="font-size:13px;color:#4A4A4A;margin-top:8px;">{{ $order->notes }}</p>
        </div>
    </div>
    @endif

    {{-- Status Update --}}
    @if(!Auth::user()->isBuyer() && !in_array($order->status, ['completed','cancelled']))
    <div class="card">
        <div class="card-header"><span class="card-title">Update Order Status</span></div>
        <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap;">
            @foreach(['confirmed','processing','completed','cancelled'] as $status)
                @if($status !== $order->status)
                <form method="POST" action="{{ route('orders.update-status', $order) }}">
                    @csrf
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button type="submit" class="btn {{ $status === 'completed' ? 'btn-primary' : ($status === 'cancelled' ? 'btn-danger' : 'btn-outline') }} btn-sm">
                        {{ ucfirst($status) }}
                    </button>
                </form>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
