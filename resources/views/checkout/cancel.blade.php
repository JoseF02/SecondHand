@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <div class="cancel-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#dc3545"/>
                        <path d="M15 9l-6 6M9 9l6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-danger">Pago Cancelado</h1>
                <p class="lead">Tu pago ha sido cancelado o no pudo ser procesado.</p>
            </div>

            @if(isset($order))
            <div class="card">
                <div class="card-header">
                    <h5>Detalles de la Orden</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Número de Orden:</h6>
                            <p><strong>{{ $order->order_number }}</strong></p>
                            
                            <h6>Fecha:</h6>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Estado:</h6>
                            <p><span class="badge bg-danger">{{ ucfirst($order->status) }}</span></p>
                            
                            <h6>Total:</h6>
                            <p><strong>${{ number_format($order->total, 2) }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="alert alert-warning mt-4">
                <h6><i class="fas fa-exclamation-triangle"></i> ¿Qué pasó?</h6>
                <p class="mb-0">El pago fue cancelado. Esto puede suceder por varias razones:</p>
                <ul class="mb-0 mt-2">
                    <li>Cancelaste el proceso de pago</li>
                    <li>Hubo un problema con tu tarjeta</li>
                    <li>La transacción expiró</li>
                    <li>Problemas de conectividad</li>
                </ul>
            </div>

            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle"></i> ¿Qué puedes hacer?</h6>
                <p class="mb-0">Puedes intentar realizar el pago nuevamente o contactarnos si necesitas ayuda.</p>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lg me-3">
                    Reintentar Pago
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">
                    Continuar Comprando
                </a>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    ¿Necesitas ayuda? <a href="mailto:support@tutienda.com">Contáctanos</a>
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.cancel-icon {
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

.bg-danger {
    background-color: #dc3545 !important;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffecb5;
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

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-lg {
    padding: 0.5rem 1rem;
    font-size: 1.25rem;
    border-radius: 0.5rem;
}

.me-3 {
    margin-right: 1rem !important;
}
</style>
@endsection
