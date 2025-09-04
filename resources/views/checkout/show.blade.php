@extends('layout')

@section('content')
<div class="container">
    <h1>Checkout</h1>

    <ul>
        @foreach($cart as $id => $qty)
            @php($product = $products[$id] ?? null)
            @if($product)
                <li>{{ $product->name }} x {{ $qty }} — ${{ number_format($product->price * $qty, 2) }}</li>
            @endif
        @endforeach
    </ul>

    <p><strong>Total: ${{ number_format($total, 2) }}</strong></p>

    <form method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <button type="submit">Confirmar compra</button>
    </form>

    <div style="margin-top:12px;">
        <a href="{{ route('cart.index') }}">Volver al carrito</a>
    </div>
</div>
@endsection


