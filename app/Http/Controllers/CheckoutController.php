<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Services\StripeService;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function show()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $total = 0;
        
        foreach ($cart as $id => $qty) {
            if (isset($products[$id])) {
                $total += $products[$id]->price * $qty;
            }
        }

        return view('checkout.show', compact('cart', 'products', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        try {
            DB::beginTransaction();

            // Calcular total
            $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
            $total = 0;
            
            foreach ($cart as $id => $qty) {
                if (isset($products[$id])) {
                    $total += $products[$id]->price * $qty;
                }
            }

            // Crear orden
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'cart_data' => $cart,
                'total' => $total,
                'status' => 'pending',
                'billing_info' => [
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'phone' => $request->billing_phone,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'postal_code' => $request->billing_postal_code,
                    'country' => $request->billing_country,
                ],
                'shipping_info' => [
                    'name' => $request->shipping_name ?: $request->billing_name,
                    'address' => $request->shipping_address ?: $request->billing_address,
                    'city' => $request->shipping_city ?: $request->billing_city,
                    'postal_code' => $request->shipping_postal_code ?: $request->billing_postal_code,
                    'country' => $request->shipping_country ?: $request->billing_country,
                ],
                'notes' => $request->notes,
            ]);

            // Crear sesión de Stripe
            $session = $this->stripeService->createCheckoutSession($order);
            
            // Actualizar orden con payment_id
            $order->update([
                'payment_id' => $session->id,
                'status' => 'processing',
            ]);

            DB::commit();

            // Redirigir a Stripe
            return redirect($session->url);

        } catch (ApiErrorException $e) {
            DB::rollBack();
            Log::error('Stripe API Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el pago. Por favor, inténtalo de nuevo.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la orden. Por favor, inténtalo de nuevo.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('products.index')->with('error', 'Sesión de pago no válida.');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
            $order = Order::where('payment_id', $sessionId)->first();

            if (!$order) {
                return redirect()->route('products.index')->with('error', 'Orden no encontrada.');
            }

            if ($this->stripeService->isPaymentSuccessful($session)) {
                $paymentInfo = $this->stripeService->getPaymentInfo($session);
                
                $order->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'stripe',
                ]);

                // Limpiar carrito
                session()->forget('cart');

                // Aquí podrías enviar emails de confirmación
                // Mail::to($order->billing_info['email'])->send(new OrderConfirmation($order));

                return view('checkout.success', compact('order', 'paymentInfo'));
            } else {
                $order->update(['status' => 'failed']);
                return redirect()->route('checkout.cancel', ['order_id' => $order->id])
                    ->with('error', 'El pago no fue procesado correctamente.');
            }

        } catch (\Exception $e) {
            Log::error('Success page error: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Error al verificar el pago.');
        }
    }

    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->canBeCancelled()) {
                $order->update(['status' => 'cancelled']);
            }
        }

        return view('checkout.cancel', compact('order'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Manejar el evento
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response('OK', 200);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        $order = Order::where('payment_id', $session->id)->first();
        
        if ($order && $order->status === 'processing') {
            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid',
            ]);
            
            Log::info("Order {$order->order_number} completed via webhook");
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        // Buscar orden por metadata o payment_intent_id
        $order = Order::where('payment_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->update([
                'status' => 'failed',
                'payment_status' => 'failed',
            ]);
            
            Log::info("Order {$order->order_number} payment failed via webhook");
        }
    }
}


