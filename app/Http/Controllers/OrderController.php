<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function sellIndex()
    {
        // Show all active crops from farmers for buyers.
        $crops = Crop::active()->with('user')->latest()->paginate(12);
        return view('orders.sell', compact('crops'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::with('crop.user');

        if ($user->isFarmer()) {
            $query->whereHas('crop', fn ($cropQuery) => $cropQuery->where('user_id', $user->id));
        } elseif ($user->isBuyer()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders   = $query->latest()->paginate(10);
        $statuses = Order::STATUSES;

        return view('orders.index', compact('orders', 'statuses'));
    }

    public function create(Request $request)
    {
        $crop = Crop::findOrFail($request->crop_id);

        if (Auth::user()->isFarmer() && $crop->user_id === Auth::id()) {
            return redirect()->route('orders.sell')->with('error', 'You cannot buy your own crop listing.');
        }

        return view('orders.create', compact('crop'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'crop_id'        => 'required|exists:crops,id',
            'buyer_name'     => 'required|string|max:255',
            'buyer_phone'    => 'required|string|max:15',
            'buyer_email'    => 'nullable|email',
            'quantity'       => 'required|numeric|min:0.1',
            'price_per_unit' => 'required|numeric|min:0',
            'payment_method' => 'required|in:' . implode(',', Order::PAYMENT_METHODS),
            'notes'          => 'nullable|string|max:500',
            'delivery_date'  => 'nullable|date|after:today',
        ]);

        $crop = Crop::findOrFail($request->crop_id);

        if (Auth::user()->isFarmer() && $crop->user_id === Auth::id()) {
            return back()->with('error', 'You cannot buy your own crop listing.');
        }

        $order = Order::create([
            'user_id'        => Auth::id(),
            'crop_id'        => $crop->id,
            'buyer_name'     => $request->buyer_name,
            'buyer_phone'    => $request->buyer_phone,
            'buyer_email'    => $request->buyer_email,
            'quantity'       => $request->quantity,
            'price_per_unit' => $request->price_per_unit,
            'total_amount'   => $request->quantity * $request->price_per_unit,
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
            'delivery_date'  => $request->delivery_date,
            'status'         => 'pending',
        ]);

        if ($request->payment_method === 'upi') {
            return redirect()->route('payment.checkout', ['order_id' => $order->id]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully! Order #' . $order->id);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $statuses = Order::STATUSES;
        return view('orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['status' => 'required|in:' . implode(',', Order::STATUSES)]);
        $order->update($request->only('status', 'notes'));
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['status' => 'required|in:' . implode(',', Order::STATUSES)]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated to ' . ucfirst($request->status));
    }
}
