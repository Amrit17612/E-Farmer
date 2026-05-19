<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Exception;

class PaymentController extends Controller
{
    private $razorpayId;
    private $razorpayKey;

    public function __construct()
    {
        $this->razorpayId = config('services.razorpay.key');
        $this->razorpayKey = config('services.razorpay.secret');
    }

    public function showCheckout(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        
        // Ensure the order belongs to the user or is a valid order to pay for
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.checkout', compact('order'));
    }

    public function processPayment(Request $request)
    {
        $input = $request->all();
        
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            $paymentId = $input['razorpay_payment_id'];
            
            try {
                // Check if it is a Sandbox/Mock Payment
                if (str_starts_with($paymentId, 'pay_mock_')) {
                    $order = Order::findOrFail($request->order_id);
                    $order->update([
                        'status' => 'confirmed',
                        'notes' => ($order->notes ? $order->notes . "\n" : "") . "Payment Successful (Sandbox Simulation). ID: " . $paymentId
                    ]);
                    return redirect()->route('orders.index')->with('success', 'Payment Successful! Your order has been confirmed.');
                }

                $api = new Api($this->razorpayId, $this->razorpayKey);
                $payment = $api->payment->fetch($paymentId);
                $order = Order::findOrFail($request->order_id);

                // Capture the payment
                $api->payment->fetch($paymentId)->capture(array('amount' => $payment['amount']));
                
                // Update order status
                $order->update([
                    'status' => 'confirmed',
                    'notes' => ($order->notes ? $order->notes . "\n" : "") . "Payment Successful. Razorpay ID: " . $paymentId
                ]);

                return redirect()->route('orders.index')->with('success', 'Payment Successful! Your order has been confirmed.');

            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Payment capture failed: ' . $e->getMessage());
            }
        }
    }
}
