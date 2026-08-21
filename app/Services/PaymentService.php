<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Process order payment with gateway driver abstraction
     */
    public function processPayment(Order $order, string $gateway, array $payload = []): array
    {
        $gatewayLower = Str::lower($gateway);

        switch ($gatewayLower) {
            case 'stripe':
                return $this->processStripe($order, $payload);
            case 'sslcommerz':
                return $this->processSSLCommerz($order, $payload);
            case 'bkash':
                return $this->processBkash($order, $payload);
            case 'cod':
            default:
                return $this->processCashOnDelivery($order);
        }
    }

    protected function processCashOnDelivery(Order $order): array
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'transaction_id' => 'COD-' . Str::upper(Str::random(10)),
            'amount' => $order->total_amount,
            'status' => 'pending',
            'payload' => ['method' => 'Cash on Delivery'],
        ]);

        $order->update([
            'payment_status' => 'pending',
            'payment_method' => 'cod',
        ]);

        return [
            'success' => true,
            'message' => 'Order placed successfully with Cash on Delivery.',
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
        ];
    }

    protected function processStripe(Order $order, array $payload): array
    {
        $txId = 'STRIPE-' . Str::upper(Str::random(12));
        $payment = Payment::create([
            'order_id' => $order->id,
            'gateway' => 'stripe',
            'transaction_id' => $txId,
            'amount' => $order->total_amount,
            'status' => 'completed',
            'payload' => $payload,
        ]);

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
        ]);

        return [
            'success' => true,
            'message' => 'Stripe payment processed successfully.',
            'transaction_id' => $txId,
        ];
    }

    protected function processSSLCommerz(Order $order, array $payload): array
    {
        $txId = 'SSL-' . Str::upper(Str::random(12));
        Payment::create([
            'order_id' => $order->id,
            'gateway' => 'sslcommerz',
            'transaction_id' => $txId,
            'amount' => $order->total_amount,
            'status' => 'completed',
            'payload' => $payload,
        ]);

        $order->update(['payment_status' => 'paid', 'payment_method' => 'sslcommerz']);

        return ['success' => true, 'message' => 'SSLCommerz payment confirmed.', 'transaction_id' => $txId];
    }

    protected function processBkash(Order $order, array $payload): array
    {
        $txId = 'BKASH-' . Str::upper(Str::random(10));
        Payment::create([
            'order_id' => $order->id,
            'gateway' => 'bkash',
            'transaction_id' => $txId,
            'amount' => $order->total_amount,
            'status' => 'completed',
            'payload' => $payload,
        ]);

        $order->update(['payment_status' => 'paid', 'payment_method' => 'bkash']);

        return ['success' => true, 'message' => 'bKash payment successful.', 'transaction_id' => $txId];
    }
}
