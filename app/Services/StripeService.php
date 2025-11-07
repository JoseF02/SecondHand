<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear sesión de checkout de Stripe
     */
    public function createCheckoutSession(Order $order): Session
    {
        $lineItems = [];
        
        foreach ($order->cart_data as $productId => $quantity) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->description ?? '',
                        ],
                        'unit_amount' => $product->price * 100, // Stripe usa centavos
                    ],
                    'quantity' => $quantity,
                ];
            }
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel') . '?order_id=' . $order->id,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
            'customer_email' => $order->billing_info['email'] ?? null,
        ]);

        return $session;
    }

    /**
     * Recuperar sesión de checkout
     */
    public function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }

    /**
     * Verificar si el pago fue exitoso
     */
    public function isPaymentSuccessful(Session $session): bool
    {
        return $session->payment_status === 'paid';
    }

    /**
     * Obtener información del pago
     */
    public function getPaymentInfo(Session $session): array
    {
        return [
            'payment_intent_id' => $session->payment_intent,
            'amount_total' => $session->amount_total,
            'currency' => $session->currency,
            'payment_status' => $session->payment_status,
            'customer_email' => $session->customer_email,
        ];
    }
}
