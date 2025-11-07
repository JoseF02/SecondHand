@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <div class="success-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#28a745"/>
                        <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-success">¡Pago Exitoso!</h1>
                <p class="lead">Tu compra ha sido procesada correctamente.</p>
            </div>

            @if(isset($order))
            <div class="card">
                <div class="card-header">
                    <h5>Detalles de tu Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Número de Orden:</h6>
                            <p><strong>{{ $order->order_number }}</strong></p>
                            
                            <h6>Fecha:</h6>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            
                            <h6>Total Pagado:</h6>
                            <p class="text-success"><strong>${{ number_format($order->total, 2) }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Estado:</h6>
                            <p><span class="badge bg-success">{{ ucfirst($order->status) }}</span></p>
                            
                            @if(isset($paymentInfo))
                            <h6>Método de Pago:</h6>
                            <p>Tarjeta de Crédito/Débito</p>
                            
                            <h6>ID de Transacción:</h6>
                            <p><small class="text-muted">{{ $paymentInfo['payment_intent_id'] ?? 'N/A' }}</small></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Productos Comprados</h5>
                </div>
                <div class="card-body">
                    @foreach($order->getProducts() as $product)
                        @php($quantity = $order->cart_data[$product->id] ?? 1)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small class="text-muted">Cantidad: {{ $quantity }}</small>
                            </div>
                            <div class="text-end">
                                ${{ number_format($product->price * $quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Información de Envío</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Nombre:</h6>
                            <p>{{ $order->shipping_info['name'] ?? $order->billing_info['name'] }}</p>
                            
                            <h6>Dirección:</h6>
                            <p>{{ $order->shipping_info['address'] ?? $order->billing_info['address'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Ciudad:</h6>
                            <p>{{ $order->shipping_info['city'] ?? $order->billing_info['city'] }}</p>
                            
                            <h6>Código Postal:</h6>
                            <p>{{ $order->shipping_info['postal_code'] ?? $order->billing_info['postal_code'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="alert alert-info mt-4">
                <h6><i class="fas fa-envelope"></i> Confirmación por Email</h6>
                <p class="mb-0">Hemos enviado un email de confirmación a <strong>{{ $order->billing_info['email'] ?? 'tu dirección de email' }}</strong> con todos los detalles de tu pedido.</p>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    Continuar Comprando
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    margin-bottom: 1rem;
}

.card {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.card-body {
    padding: 1rem;
}

.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
}

.bg-success {
    background-color: #198754 !important;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.375rem;
}

.btn-primary {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-lg {
    padding: 0.5rem 1rem;
    font-size: 1.25rem;
    border-radius: 0.5rem;
}
</style>
@endsection


