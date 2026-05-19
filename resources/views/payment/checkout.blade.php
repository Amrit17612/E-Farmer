@extends('layouts.app')

@section('title', 'Secure Checkout - eFarmar')

@push('styles')
<style>
    /* Premium checkout layout */
    .checkout-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    
    .checkout-main-header {
        margin-bottom: 32px;
        text-align: center;
    }
    
    .checkout-main-header h1 {
        font-size: 32px;
        font-weight: 800;
        color: #1B5E20;
        margin-bottom: 8px;
    }
    
    .checkout-main-header p {
        font-size: 15px;
        color: #666;
    }
    
    .checkout-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 32px;
    }
    
    @media (max-width: 868px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .checkout-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid #E8F5E9;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .card-header-premium {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        padding: 24px;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header-premium i {
        font-size: 24px;
    }
    
    .card-header-premium h2 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
    }
    
    .card-body {
        padding: 28px;
        flex-grow: 1;
    }
    
    .crop-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #E8F5E9;
        color: #2E7D32;
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 24px;
    }
    
    .summary-details {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        color: #555;
    }
    
    .detail-row.divider {
        height: 1px;
        background: #E8F5E9;
        margin: 8px 0;
    }
    
    .detail-row .label {
        font-weight: 500;
    }
    
    .detail-row .value {
        font-weight: 700;
        color: #333;
    }
    
    .detail-row.total-row {
        font-size: 20px;
        color: #2E7D32;
        font-weight: 800;
    }
    
    .detail-row.total-row .value {
        color: #2E7D32;
        font-size: 22px;
    }
    
    .security-seal {
        margin-top: 32px;
        background: #FAFAFA;
        border: 1px dashed #E0E0E0;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .security-seal i {
        font-size: 28px;
        color: #2E7D32;
    }
    
    .security-seal strong {
        font-size: 13px;
        color: #333;
        display: block;
    }
    
    .security-seal p {
        font-size: 11px;
        color: #777;
        margin: 2px 0 0 0;
    }
    
    /* Payment selection styling */
    .options-body {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .payment-method-btn {
        border: 2px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px;
        position: relative;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 16px;
        text-align: left;
    }
    
    .payment-method-btn:hover {
        border-color: #A3D9C9;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }
    
    .payment-method-btn.recommended {
        border-color: #2E7D32;
        background: linear-gradient(180deg, #FFFFFF, #F1F8F5);
        box-shadow: 0 10px 25px rgba(46,125,50,0.05);
    }
    
    .recommended-badge {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #2E7D32;
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 8px rgba(46,125,50,0.2);
    }
    
    .method-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .method-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    
    .method-icon-wrapper.rzp-color {
        background: #EBF4FF;
        color: #3182CE;
    }
    
    .method-icon-wrapper.sim-color {
        background: #E8F5E9;
        color: #2E7D32;
    }
    
    .method-text h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2D3748;
    }
    
    .method-text p {
        font-size: 12px;
        color: #718096;
        margin: 0;
    }
    
    .select-btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.2s;
    }
    
    .select-btn.primary-solid {
        background: #3182CE;
        color: #ffffff;
    }
    
    .select-btn.primary-solid:hover {
        background: #2B6CB0;
    }
    
    .select-btn.accent-solid {
        background: #2E7D32;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(46,125,50,0.15);
    }
    
    .select-btn.accent-solid:hover {
        background: #1B5E20;
    }
    
    /* Phone simulator modal styles */
    .sim-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
    }
    
    .sim-modal.hidden {
        display: none !important;
    }
    
    .sim-modal-backdrop {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(10, 25, 15, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    
    .sim-phone-wrapper {
        position: relative;
        z-index: 2;
        transform: scale(0.95);
        animation: phonePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    
    @keyframes phonePop {
        to { transform: scale(1); }
    }
    
    .sim-phone-body {
        width: 340px;
        height: 680px;
        background: #121212;
        border: 14px solid #1A1A1A;
        border-radius: 44px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.1) inset;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .phone-notch {
        width: 130px;
        height: 24px;
        background: #1A1A1A;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    
    .phone-status-bar {
        height: 38px;
        padding: 0 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        z-index: 5;
        background: transparent;
        pointer-events: none;
    }
    
    .status-icons {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    
    .phone-screen {
        flex-grow: 1;
        background: #FAFAFA;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding-top: 10px;
    }
    
    .close-phone-btn {
        position: absolute;
        top: 45px;
        right: 16px;
        background: none;
        border: none;
        font-size: 26px;
        color: #EF5350;
        cursor: pointer;
        z-index: 10;
        transition: 0.2s;
        padding: 0;
        line-height: 1;
    }
    
    .close-phone-btn:hover {
        transform: scale(1.1);
        color: #D32F2F;
    }
    
    .state-view {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        padding: 24px;
        animation: fadeIn 0.3s ease-in-out forwards;
    }
    
    .state-view.hidden {
        display: none !important;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* State 1: QR view */
    .merchant-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
        background: #ffffff;
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #E8F5E9;
    }
    
    .merchant-logo {
        width: 38px;
        height: 38px;
        background: #2E7D32;
        color: #ffffff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .merchant-info-block h4 {
        font-size: 13px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }
    
    .merchant-info-block p {
        font-size: 10px;
        color: #666;
        margin: 2px 0 0 0;
    }
    
    .amount-container {
        text-align: center;
        margin: 20px 0 15px 0;
    }
    
    .amount-label {
        font-size: 11px;
        color: #888;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .amount-val {
        font-size: 24px;
        font-weight: 800;
        color: #2E7D32;
        margin: 4px 0 0 0;
    }
    
    .qr-code-box {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 16px;
        width: 170px;
        height: 170px;
        margin: 0 auto;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .qr-svg {
        width: 100%;
        height: 100%;
    }
    
    .scan-line {
        position: absolute;
        width: 90%;
        height: 3px;
        background: #4CAF50;
        box-shadow: 0 0 8px #4CAF50;
        top: 10px;
        animation: scanAnim 3s linear infinite;
        z-index: 2;
    }
    
    @keyframes scanAnim {
        0%, 100% { top: 10px; }
        50% { top: 155px; }
    }
    
    .qr-corner {
        position: absolute;
        width: 14px;
        height: 14px;
        border: 3px solid #2E7D32;
        z-index: 1;
    }
    
    .qr-corner.top-left { top: 8px; left: 8px; border-right: none; border-bottom: none; }
    .qr-corner.top-right { top: 8px; right: 8px; border-left: none; border-bottom: none; }
    .qr-corner.bottom-left { bottom: 8px; left: 8px; border-right: none; border-top: none; }
    .qr-corner.bottom-right { bottom: 8px; right: 8px; border-left: none; border-top: none; }
    
    .qr-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }
    
    .scan-instructions {
        font-size: 11px;
        color: #666;
        margin: 0;
        font-weight: 500;
    }
    
    .phone-action-btn {
        width: 100%;
        background: #2E7D32;
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(46,125,50,0.25);
        transition: 0.2s;
    }
    
    .phone-action-btn:active {
        transform: scale(0.98);
        background: #1B5E20;
    }
    
    /* State 2: Pin View */
    .pin-header {
        margin-top: 35px;
        background: #ffffff;
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #E8F5E9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .bank-info {
        display: flex;
        flex-direction: column;
    }
    
    .bank-name {
        font-size: 12px;
        font-weight: 700;
        color: #1A365D;
    }
    
    .payment-to {
        font-size: 10px;
        color: #666;
        margin-top: 2px;
    }
    
    .payment-amount {
        font-size: 16px;
        font-weight: 800;
        color: #2E7D32;
    }
    
    .pin-input-area {
        margin: 40px auto 20px auto;
        text-align: center;
    }
    
    .pin-input-area label {
        font-size: 11px;
        font-weight: 600;
        color: #4A5568;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 16px;
    }
    
    .pin-dots-row {
        display: flex;
        gap: 18px;
        justify-content: center;
    }
    
    .pin-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #CBD5E0;
        transition: all 0.15s ease;
    }
    
    .pin-dot-filled {
        background: #2E7D32;
        border-color: #2E7D32;
        transform: scale(1.15);
    }
    
    .keypad-container {
        margin-top: auto;
        background: #ffffff;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        margin-left: -24px;
        margin-right: -24px;
        margin-bottom: -24px;
        padding: 20px 24px 28px 24px;
        box-shadow: 0 -8px 24px rgba(0,0,0,0.04);
        border-top: 1px solid #F0F4F1;
    }
    
    .keypad-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .keypad-grid button {
        background: #F8FAFC;
        border: none;
        border-radius: 12px;
        height: 48px;
        font-size: 18px;
        font-weight: 700;
        color: #2D3748;
        cursor: pointer;
        transition: background 0.1s, transform 0.1s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .keypad-grid button:active {
        background: #E2E8F0;
        transform: scale(0.95);
    }
    
    .keypad-grid button.keypad-special {
        background: #F1F5F9;
        color: #475569;
        font-size: 16px;
    }
    
    .keypad-grid button.submit-key {
        background: #E8F5E9;
        color: #2E7D32;
        font-size: 18px;
    }
    
    .keypad-grid button.submit-key:active {
        background: #C8E6C9;
    }
    
    /* State 3: Loading View */
    .loader-content {
        margin: auto;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .premium-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #E8F5E9;
        border-top: 4px solid #2E7D32;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 24px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loader-content h3 {
        font-size: 15px;
        font-weight: 700;
        color: #2D3748;
        margin: 0 0 8px 0;
    }
    
    .loader-content p {
        font-size: 11px;
        color: #718096;
        margin: 0 0 24px 0;
    }
    
    .secure-network-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EDF2F7;
        color: #4A5568;
        font-size: 10px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 20px;
    }
    
    /* State 4: Success View */
    .success-content {
        margin: auto;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    
    .success-checkmark-wrapper {
        width: 70px;
        height: 70px;
        margin-bottom: 20px;
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .scale-0 {
        transform: scale(0);
    }
    
    .checkmark-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #E8F5E9;
        border: 3px solid #2E7D32;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(46,125,50,0.1);
    }
    
    .checkmark-stem {
        width: 4px;
        height: 24px;
        background-color: #2E7D32;
        position: absolute;
        left: 36px;
        top: 18px;
        transform: rotate(45deg);
        border-radius: 2px;
    }
    
    .checkmark-kick {
        width: 13px;
        height: 4px;
        background-color: #2E7D32;
        position: absolute;
        left: 22px;
        top: 38px;
        transform: rotate(45deg);
        border-radius: 2px;
    }
    
    .success-content h2 {
        font-size: 18px;
        font-weight: 800;
        color: #2E7D32;
        margin: 0 0 6px 0;
    }
    
    .congrats-subtitle {
        font-size: 11px;
        color: #718096;
        margin: 0 0 20px 0;
        line-height: 1.4;
        padding: 0 10px;
    }
    
    .receipt-box {
        background: #ffffff;
        border: 1px solid #EDF2F7;
        border-radius: 16px;
        padding: 16px;
        width: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .receipt-row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #718096;
    }
    
    .receipt-row strong {
        color: #2D3748;
    }
    
    .receipt-row strong.green-text {
        color: #2E7D32;
        font-size: 13px;
        font-weight: 800;
    }
    
    .receipt-row .tx-id-val {
        font-family: monospace;
        font-size: 9px;
        color: #4A5568;
        background: #EDF2F7;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .redirect-notice {
        margin-top: 30px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: #718096;
    }
    
    /* Flashing dots for redirection */
    .dot-flashing {
        position: relative;
        width: 6px;
        height: 6px;
        border-radius: 5px;
        background-color: #2E7D32;
        color: #2E7D32;
        animation: dotFlashing 1s infinite linear alternate;
        animation-delay: .5s;
    }
    
    .dot-flashing::before, .dot-flashing::after {
        content: '';
        display: inline-block;
        position: absolute;
        top: 0;
    }
    
    .dot-flashing::before {
        left: -10px;
        width: 6px;
        height: 6px;
        border-radius: 5px;
        background-color: #2E7D32;
        color: #2E7D32;
        animation: dotFlashing 1s infinite alternate;
        animation-delay: 0s;
    }
    
    .dot-flashing::after {
        left: 10px;
        width: 6px;
        height: 6px;
        border-radius: 5px;
        background-color: #2E7D32;
        color: #2E7D32;
        animation: dotFlashing 1s infinite alternate;
        animation-delay: 1s;
    }
    
    @keyframes dotFlashing {
        0% { background-color: #2E7D32; }
        50%, 100% { background-color: #E8F5E9; }
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <!-- Header Page title -->
    <div class="checkout-main-header">
        <h1>Secure Checkout</h1>
        <p>Choose your preferred secure payment method to confirm your order.</p>
    </div>

    <div class="checkout-grid">
        <!-- Left Side: Order Summary -->
        <div class="checkout-card summary-card">
            <div class="card-header-premium">
                <i class="bi bi-basket-fill"></i>
                <h2>Order Summary</h2>
            </div>
            <div class="card-body">
                <div class="crop-badge">
                    <i class="bi bi-leaf"></i> {{ $order->crop->name }}
                </div>
                <div class="summary-details">
                    <div class="detail-row">
                        <span class="label">Farmer</span>
                        <span class="value">{{ $order->crop->user->name ?? '—' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Quantity</span>
                        <span class="value">{{ number_format($order->quantity, 1) }} {{ $order->crop->unit }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Price per Unit</span>
                        <span class="value">₹{{ number_format($order->price_per_unit) }}</span>
                    </div>
                    <div class="detail-row divider"></div>
                    <div class="detail-row total-row">
                        <span class="label">Grand Total</span>
                        <span class="value">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <div class="security-seal">
                    <i class="bi bi-shield-fill-check"></i>
                    <div>
                        <strong>256-Bit SSL Secured</strong>
                        <p>Your transaction is encrypted and completely safe.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Payment Methods -->
        <div class="checkout-card options-card">
            <div class="card-header-premium">
                <i class="bi bi-credit-card-2-front-fill"></i>
                <h2>Select Payment Method</h2>
            </div>
            <div class="card-body options-body">
                <!-- Option 1: Live Razorpay Gateway -->
                <div class="payment-method-btn" id="rzp-button-card">
                    <div class="method-info">
                        <div class="method-icon-wrapper rzp-color">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="method-text">
                            <h3>Razorpay Live Gateway</h3>
                            <p>Cards, NetBanking, GooglePay, Real UPI</p>
                        </div>
                    </div>
                    <button class="select-btn primary-solid" id="rzp-button1">
                        Pay Now <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <!-- Option 2: Simulated Sandbox UPI (Recommended) -->
                <div class="payment-method-btn recommended" id="sim-button-card">
                    <div class="recommended-badge"><i class="bi bi-star-fill"></i> RECOMMENDED FOR TESTING</div>
                    <div class="method-info">
                        <div class="method-icon-wrapper sim-color">
                            <i class="bi bi-phone-vibrate-fill"></i>
                        </div>
                        <div class="method-text">
                            <h3>UPI Sandbox Simulator</h3>
                            <p>Instant Offline PIN Keypad Simulation (100% Reliable)</p>
                        </div>
                    </div>
                    <button class="select-btn accent-solid" id="sim-button">
                        Simulate UPI <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form to process payment -->
    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    </form>
</div>

<!-- ==========================================================
     INTERACTIVE UPI PHONE SIMULATOR MODAL (Forest Green Theme)
     ========================================================== -->
<div id="upi-simulator-modal" class="sim-modal hidden">
    <div class="sim-modal-backdrop"></div>
    <div class="sim-phone-wrapper">
        <!-- Phone Device Frame -->
        <div class="sim-phone-body">
            <!-- Top Camera Notch -->
            <div class="phone-notch"></div>
            
            <!-- Status Bar -->
            <div class="phone-status-bar">
                <span class="status-time" id="status-time">09:41</span>
                <div class="status-icons">
                    <i class="bi bi-reception-4"></i>
                    <i class="bi bi-wifi"></i>
                    <i class="bi bi-battery-full"></i>
                </div>
            </div>

            <!-- Phone Screen Content Container -->
            <div class="phone-screen">
                <!-- Close Modal Button -->
                <button class="close-phone-btn" id="close-sim-btn" title="Cancel Payment">
                    <i class="bi bi-x-circle-fill"></i>
                </button>

                <!-- ================= STATE 1: QR CODE / SCAN SCREEN ================= -->
                <div class="state-view" id="sim-state-qr">
                    <div class="merchant-header">
                        <div class="merchant-logo">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="merchant-info-block">
                            <h4>eFarmar Merchant</h4>
                            <p>UPI ID: efarmar@ybl</p>
                        </div>
                    </div>

                    <div class="amount-container">
                        <span class="amount-label">Paying secure amount</span>
                        <h2 class="amount-val">₹{{ number_format($order->total_amount, 2) }}</h2>
                    </div>

                    <!-- Visual QR Code Box with scanning effect -->
                    <div class="qr-code-box">
                        <div class="scan-line"></div>
                        <!-- Beautiful Detailed inline SVG QR Code Mockup -->
                        <svg viewBox="0 0 100 100" class="qr-svg">
                            <!-- Background -->
                            <rect width="100" height="100" fill="#FFFFFF"/>
                            <!-- Position Anchors -->
                            <!-- Top Left -->
                            <rect x="5" y="5" width="25" height="25" fill="#1B5E20"/>
                            <rect x="10" y="10" width="15" height="15" fill="#FFFFFF"/>
                            <rect x="13" y="13" width="9" height="9" fill="#1B5E20"/>
                            <!-- Top Right -->
                            <rect x="70" y="5" width="25" height="25" fill="#1B5E20"/>
                            <rect x="75" y="10" width="15" height="15" fill="#FFFFFF"/>
                            <rect x="78" y="13" width="9" height="9" fill="#1B5E20"/>
                            <!-- Bottom Left -->
                            <rect x="5" y="70" width="25" height="25" fill="#1B5E20"/>
                            <rect x="10" y="75" width="15" height="15" fill="#FFFFFF"/>
                            <rect x="13" y="78" width="9" height="9" fill="#1B5E20"/>
                            <!-- Random QR Bits -->
                            <rect x="35" y="5" width="5" height="5" fill="#1B5E20"/>
                            <rect x="45" y="5" width="10" height="5" fill="#1B5E20"/>
                            <rect x="60" y="10" width="5" height="10" fill="#1B5E20"/>
                            <rect x="35" y="20" width="15" height="5" fill="#1B5E20"/>
                            <rect x="55" y="20" width="5" height="5" fill="#1B5E20"/>
                            
                            <rect x="5" y="35" width="5" height="10" fill="#1B5E20"/>
                            <rect x="15" y="45" width="10" height="5" fill="#1B5E20"/>
                            <rect x="35" y="35" width="20" height="5" fill="#1B5E20"/>
                            <rect x="40" y="45" width="5" height="15" fill="#1B5E20"/>
                            <rect x="50" y="50" width="15" height="5" fill="#1B5E20"/>
                            
                            <rect x="75" y="35" width="10" height="5" fill="#1B5E20"/>
                            <rect x="70" y="45" width="5" height="15" fill="#1B5E20"/>
                            <rect x="85" y="55" width="10" height="10" fill="#1B5E20"/>
                            
                            <rect x="35" y="70" width="5" height="15" fill="#1B5E20"/>
                            <rect x="45" y="75" width="15" height="5" fill="#1B5E20"/>
                            <rect x="50" y="85" width="10" height="10" fill="#1B5E20"/>
                            <rect x="70" y="75" width="10" height="5" fill="#1B5E20"/>
                            <rect x="75" y="85" width="20" height="5" fill="#1B5E20"/>
                        </svg>
                        <div class="qr-corner top-left"></div>
                        <div class="qr-corner top-right"></div>
                        <div class="qr-corner bottom-left"></div>
                        <div class="qr-corner bottom-right"></div>
                    </div>

                    <div class="qr-footer">
                        <p class="scan-instructions"><i class="bi bi-qr-code-scan"></i> Scan or click below to simulate</p>
                        <button class="phone-action-btn" id="qr-next-btn">
                            Pay ₹{{ number_format($order->total_amount) }} Now
                        </button>
                    </div>
                </div>

                <!-- ================= STATE 2: ENTER UPI PIN KEYPAD ================= -->
                <div class="state-view hidden" id="sim-state-pin">
                    <div class="pin-header">
                        <div class="bank-info">
                            <span class="bank-name">State Bank of India</span>
                            <span class="payment-to">To: eFarmar Merchant</span>
                        </div>
                        <div class="payment-amount">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </div>
                    </div>

                    <div class="pin-input-area">
                        <label>ENTER 4-DIGIT UPI PIN</label>
                        <div class="pin-dots-row">
                            <div class="pin-dot"></div>
                            <div class="pin-dot"></div>
                            <div class="pin-dot"></div>
                            <div class="pin-dot"></div>
                        </div>
                    </div>

                    <!-- Custom Numeric Keypad -->
                    <div class="keypad-container">
                        <div class="keypad-grid">
                            <button data-key="1">1</button>
                            <button data-key="2">2</button>
                            <button data-key="3">3</button>
                            <button data-key="4">4</button>
                            <button data-key="5">5</button>
                            <button data-key="6">6</button>
                            <button data-key="7">7</button>
                            <button data-key="8">8</button>
                            <button data-key="9">9</button>
                            <button data-key="pin-back" class="keypad-special" title="Backspace">
                                <i class="bi bi-backspace-fill"></i>
                            </button>
                            <button data-key="0">0</button>
                            <button data-key="pin-submit" class="keypad-special submit-key" title="Submit">
                                <i class="bi bi-check-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= STATE 3: PROCESSING SERVER CAPTURE ================= -->
                <div class="state-view hidden" id="sim-state-loading">
                    <div class="loader-content">
                        <div class="premium-spinner"></div>
                        <h3>Contacting Payment Server...</h3>
                        <p>Processing bank verification secure handshake</p>
                        <div class="secure-network-badge">
                            <i class="bi bi-shield-lock-fill"></i> Secure Bank Gateway Network
                        </div>
                    </div>
                </div>

                <!-- ================= STATE 4: SUCCESS CONGRATS SCREEN ================= -->
                <div class="state-view hidden" id="sim-state-success">
                    <div class="success-content">
                        <div class="success-checkmark-wrapper scale-0">
                            <div class="checkmark-circle">
                                <div class="checkmark-stem"></div>
                                <div class="checkmark-kick"></div>
                            </div>
                        </div>
                        <h2>Transaction Successful!</h2>
                        <p class="congrats-subtitle">Your purchase order is confirmed and sent to the farmer.</p>
                        
                        <div class="receipt-box">
                            <div class="receipt-row">
                                <span>Paid to</span>
                                <strong>eFarmar</strong>
                            </div>
                            <div class="receipt-row">
                                <span>Amount</span>
                                <strong class="green-text">₹{{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                            <div class="receipt-row">
                                <span>Transaction ID</span>
                                <span class="tx-id-val" id="mock-tx-id">pay_mock_xxxxxxxxxxxxxx</span>
                            </div>
                        </div>
                        
                        <div class="redirect-notice">
                            <div class="dot-flashing"></div>
                            <span>Redirecting to Mandi Purchases...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderId = "{{ $order->id }}";
        const orderTotal = "{{ $order->total_amount }}";

        // ==========================================================
        // METHOD 1: RAZORPAY LIVE GATEWAY INTEGRATION
        // ==========================================================
        var options = {
            "key": "{{ config('services.razorpay.key') }}",
            "amount": {{ (int) ($order->total_amount * 100) }},
            "currency": "INR",
            "name": "eFarmar",
            "description": "Secure Payment for Order #" + orderId,
            "image": "https://cdn-icons-png.flaticon.com/512/2362/2362381.png",
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('payment-form').submit();
            },
            "prefill": {
                "name": "{{ auth()->user()->name }}",
                "email": "{{ auth()->user()->email }}",
                "contact": "{{ auth()->user()->phone ?? '' }}"
            },
            "theme": {
                "color": "#2E7D32"
            }
        };

        let rzp1 = null;
        try {
            if (typeof Razorpay !== 'undefined') {
                rzp1 = new Razorpay(options);
            }
        } catch(err) {
            console.error("Razorpay initialization failed:", err);
        }

        const rzpBtn = document.getElementById('rzp-button1');
        if (rzpBtn) {
            rzpBtn.onclick = function(e){
                e.preventDefault();
                if (rzp1) {
                    try {
                        rzp1.open();
                    } catch(err) {
                        console.error("Failed to open Razorpay modal:", err);
                        alert("Razorpay Live Gateway script encountered an issue. Initiating Sandbox UPI Simulator instead!");
                        startUpiSimulation();
                    }
                } else {
                    alert("Razorpay Live Gateway could not load (possibly offline or blocked). Starting Sandbox Simulator instead!");
                    startUpiSimulation();
                }
            }
        }

        // Card container clickable triggers as well
        document.getElementById('rzp-button-card').onclick = function(e) {
            if (e.target !== rzpBtn) {
                rzpBtn.click();
            }
        };

        // ==========================================================
        // METHOD 2: INTERACTIVE SANDBOX SIMULATOR
        // ==========================================================
        const simBtn = document.getElementById('sim-button');
        const modal = document.getElementById('upi-simulator-modal');
        const closeBtn = document.getElementById('close-sim-btn');
        const qrNextBtn = document.getElementById('qr-next-btn');

        // Keypad Pin logic
        const keypadBtns = document.querySelectorAll('.keypad-grid button');
        const pinDots = document.querySelectorAll('.pin-dot');
        let enteredPin = "";

        const startUpiSimulation = () => {
            modal.classList.remove('hidden');
            showState('qr');
            updateClock();
        };

        if (simBtn) {
            simBtn.onclick = function(e) {
                e.preventDefault();
                startUpiSimulation();
            };
        }

        document.getElementById('sim-button-card').onclick = function(e) {
            if (e.target !== simBtn) {
                simBtn.click();
            }
        };

        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.classList.add('hidden');
            };
        }

        // State switcher
        const showState = (state) => {
            document.querySelectorAll('.state-view').forEach(view => view.classList.add('hidden'));
            document.getElementById(`sim-state-${state}`).classList.remove('hidden');
        };

        // Next from QR
        if (qrNextBtn) {
            qrNextBtn.onclick = () => {
                enteredPin = "";
                updatePinDots();
                showState('pin');
            };
        }

        // Pin keypad click handler
        keypadBtns.forEach(btn => {
            btn.onclick = () => {
                const key = btn.getAttribute('data-key');
                if (key === 'pin-back') {
                    if (enteredPin.length > 0) {
                        enteredPin = enteredPin.slice(0, -1);
                        updatePinDots();
                    }
                } else if (key === 'pin-submit') {
                    if (enteredPin.length < 4) {
                        alert("Please enter a valid 4-digit UPI PIN");
                        return;
                    }
                    
                    // Show loading contact screen
                    showState('loading');
                    
                    // Simulate processing delay
                    setTimeout(() => {
                        // Generate dynamic mock payment id
                        const randomHex = Math.random().toString(16).substring(2, 16);
                        const mockPaymentId = `pay_mock_${randomHex}`;
                        
                        document.getElementById('mock-tx-id').innerText = mockPaymentId;
                        
                        // Show success
                        showState('success');
                        setTimeout(() => {
                            document.querySelector('.success-checkmark-wrapper').classList.remove('scale-0');
                        }, 50);

                        // Final Auto Redirect to complete checkout!
                        setTimeout(() => {
                            document.getElementById('razorpay_payment_id').value = mockPaymentId;
                            document.getElementById('payment-form').submit();
                        }, 2800);

                    }, 2000);
                } else {
                    if (enteredPin.length < 4) {
                        enteredPin += key;
                        updatePinDots();
                    }
                }
            };
        });

        // Update Pin Dots UI
        const updatePinDots = () => {
            pinDots.forEach((dot, index) => {
                if (index < enteredPin.length) {
                    dot.className = "pin-dot pin-dot-filled";
                } else {
                    dot.className = "pin-dot";
                }
            });
        };

        // Clock update function for the phone status bar
        function updateClock() {
            const timeSpan = document.getElementById('status-time');
            if (timeSpan) {
                const now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes();
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                timeSpan.innerText = hours + ':' + minutes;
            }
        }
        setInterval(updateClock, 30000);
    });
</script>
@endpush
