@extends('layout')

@section('content')
<div class="container">
    <h1>Finalizar Compra</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Resumen del Pedido -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    @foreach($cart as $id => $qty)
                        @php($product = $products[$id] ?? null)
                        @if($product)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">Cantidad: {{ $qty }}</small>
                                </div>
                                <div class="text-end">
                                    ${{ number_format($product->price * $qty, 2) }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Checkout -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                
                <!-- Información de Facturación -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Información de Facturación</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_name" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control @error('billing_name') is-invalid @enderror" 
                                       id="billing_name" name="billing_name" value="{{ old('billing_name') }}" required>
                                @error('billing_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('billing_email') is-invalid @enderror" 
                                       id="billing_email" name="billing_email" value="{{ old('billing_email') }}" required>
                                @error('billing_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_phone" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control @error('billing_phone') is-invalid @enderror" 
                                       id="billing_phone" name="billing_phone" value="{{ old('billing_phone') }}" required>
                                @error('billing_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_country" class="form-label">País *</label>
                                <select class="form-control @error('billing_country') is-invalid @enderror" 
                                        id="billing_country" name="billing_country" required>
                                    <option value="">Seleccionar país</option>
                                    <option value="AR" {{ old('billing_country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                                    <option value="BR" {{ old('billing_country') == 'BR' ? 'selected' : '' }}>Brasil</option>
                                    <option value="CL" {{ old('billing_country') == 'CL' ? 'selected' : '' }}>Chile</option>
                                    <option value="CO" {{ old('billing_country') == 'CO' ? 'selected' : '' }}>Colombia</option>
                                    <option value="MX" {{ old('billing_country') == 'MX' ? 'selected' : '' }}>México</option>
                                    <option value="PE" {{ old('billing_country') == 'PE' ? 'selected' : '' }}>Perú</option>
                                    <option value="US" {{ old('billing_country') == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                                </select>
                                @error('billing_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Dirección *</label>
                            <input type="text" class="form-control @error('billing_address') is-invalid @enderror" 
                                   id="billing_address" name="billing_address" value="{{ old('billing_address') }}" required>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label">Ciudad *</label>
                                <input type="text" class="form-control @error('billing_city') is-invalid @enderror" 
                                       id="billing_city" name="billing_city" value="{{ old('billing_city') }}" required>
                                @error('billing_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_postal_code" class="form-label">Código Postal *</label>
                                <input type="text" class="form-control @error('billing_postal_code') is-invalid @enderror" 
                                       id="billing_postal_code" name="billing_postal_code" value="{{ old('billing_postal_code') }}" required>
                                @error('billing_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Envío -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Información de Envío</h5>
                        <small class="text-muted">Dejar en blanco si es igual a la facturación</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" 
                                   id="shipping_name" name="shipping_name" value="{{ old('shipping_name') }}">
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('shipping_address') is-invalid @enderror" 
                                   id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}">
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="shipping_city" class="form-label">Ciudad</label>
                                <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" 
                                       id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}">
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_postal_code" class="form-label">Código Postal</label>
                                <input type="text" class="form-control @error('shipping_postal_code') is-invalid @enderror" 
                                       id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                                @error('shipping_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_country" class="form-label">País</label>
                                <select class="form-control @error('shipping_country') is-invalid @enderror" 
                                        id="shipping_country" name="shipping_country">
                                    <option value="">Seleccionar país</option>
                                    <option value="AR" {{ old('shipping_country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                                    <option value="BR" {{ old('shipping_country') == 'BR' ? 'selected' : '' }}>Brasil</option>
                                    <option value="CL" {{ old('shipping_country') == 'CL' ? 'selected' : '' }}>Chile</option>
                                    <option value="CO" {{ old('shipping_country') == 'CO' ? 'selected' : '' }}>Colombia</option>
                                    <option value="MX" {{ old('shipping_country') == 'MX' ? 'selected' : '' }}>México</option>
                                    <option value="PE" {{ old('shipping_country') == 'PE' ? 'selected' : '' }}>Perú</option>
                                    <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                                </select>
                                @error('shipping_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notas Adicionales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Notas Adicionales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Comentarios sobre tu pedido (opcional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" placeholder="Instrucciones especiales, comentarios, etc.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                        ← Volver al Carrito
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        Proceder al Pago →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
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

.form-label {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
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
</style>
@endsection


