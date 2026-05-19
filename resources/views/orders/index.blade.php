@extends('layouts.app')
@section('title', Auth::user()->isFarmer() ? 'Sales Orders' : (Auth::user()->isAdmin() ? 'All Orders' : 'My Purchases'))
@section('page-title', Auth::user()->isFarmer() ? 'Sales Orders' : (Auth::user()->isAdmin() ? 'All Orders' : 'My Purchases'))

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
    <div>
        <h2 style="font-size:20px;font-weight:700;">{{ Auth::user()->isFarmer() ? 'Sales Orders' : (Auth::user()->isAdmin() ? 'All Orders' : 'My Purchases') }}</h2>
        <p style="font-size:13px;color:#757575;">
            {{ Auth::user()->isFarmer() ? 'Track buyer requests for your crop listings' : (Auth::user()->isAdmin() ? 'Monitor all platform transactions' : 'Track crops you have ordered from farmers') }}
        </p>
    </div>
    @if(!Auth::user()->isFarmer() && !Auth::user()->isAdmin())
    <a href="{{ route('orders.sell') }}" class="btn btn-primary">
        <i class="bi bi-shop"></i> Browse Crops to Buy
    </a>
    @endif
</div>

{{-- Status Filter --}}
<form method="GET" action="{{ route('orders.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        @foreach(['', ...$statuses] as $s)
        <button type="submit" name="status" value="{{ $s }}"
            class="btn {{ request('status') === $s ? 'btn-primary' : 'btn-outline' }} btn-sm">
            {{ $s === '' ? 'All' : ucfirst($s) }}
        </button>
        @endforeach
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($orders->count())
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Crop</th>
                    <th>{{ Auth::user()->isBuyer() ? 'Farmer' : 'Buyer' }}</th>
                    <th>Quantity</th>
                    <th>Price/Unit</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="font-weight:600;color:#9E9E9E;">#{{ $order->id }}</td>
                    <td><strong>{{ $order->crop->name ?? '—' }}</strong></td>
                    <td>
                        @if(Auth::user()->isBuyer())
                            <div style="font-size:13px;font-weight:600;">{{ $order->crop->user->name ?? '—' }}</div>
                            <div style="font-size:11px;color:#9E9E9E;">{{ $order->crop->user->location ?? 'No location' }}</div>
                        @else
                            <div style="font-size:13px;font-weight:600;">{{ $order->buyer_name }}</div>
                            <div style="font-size:11px;color:#9E9E9E;">{{ $order->buyer_phone }}</div>
                        @endif
                    </td>
                    <td>{{ number_format($order->quantity, 1) }} {{ $order->crop->unit ?? '' }}</td>
                    <td>₹{{ number_format($order->price_per_unit) }}</td>
                    <td style="font-weight:700;color:#2E7D32;">₹{{ number_format($order->total_amount) }}</td>
                    <td><span class="badge badge-info">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></td>
                    <td>{!! $order->status_badge !!}</td>
                    <td style="font-size:12px;color:#9E9E9E;">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @if(Auth::user()->isBuyer() && $order->status === 'pending' && $order->payment_method === 'upi')
                                <a href="{{ route('payment.checkout', ['order_id' => $order->id]) }}" class="btn btn-primary btn-sm" style="background:#2D6A4F;">
                                    <i class="bi bi-credit-card-fill"></i> Pay Now
                                </a>
                            @endif

                            @if(!Auth::user()->isBuyer() && !in_array($order->status, ['completed','cancelled']))
                            <form method="POST" action="{{ route('orders.update-status', $order) }}">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-primary btn-sm" title="Mark Complete">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:16px 20px;border-top:1px solid #F0F0F0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <span style="font-size:13px;color:#757575;">{{ $orders->total() }} order(s) total</span>
            <div class="pagination">
                @if(!$orders->onFirstPage()) <a href="{{ $orders->previousPageUrl() }}" class="page-link">‹</a> @endif
                @foreach($orders->getUrlRange(1,$orders->lastPage()) as $p=>$u)
                    <a href="{{ $u }}" class="page-link {{ $orders->currentPage()===$p?'active':'' }}">{{ $p }}</a>
                @endforeach
                @if($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}" class="page-link">›</a> @endif
            </div>
        </div>
        @else
        <div style="text-align:center;padding:80px;color:#9E9E9E;">
            <i class="bi bi-bag-x" style="font-size:56px;color:#C8E6C9;display:block;margin-bottom:12px;"></i>
            <h3 style="font-size:18px;font-weight:600;color:#4A4A4A;margin-bottom:8px;">No Orders Yet</h3>
            <p>{{ Auth::user()->isFarmer() ? 'When buyers order your crops, they will appear here.' : 'When you buy crops, they will appear here.' }}</p>
            @if(Auth::user()->isBuyer())
                <a href="{{ route('orders.sell') }}" class="btn btn-primary" style="margin-top:16px;">
                    <i class="bi bi-shop"></i> Browse Crops
                </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
