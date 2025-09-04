@extends('layout')

@section('content')
<div class="container">
    <h1>Carrito</h1>
    @php($items = collect($cart))
    @php($products = \App\Models\Product::whereIn('id', $items->keys())->get()->keyBy('id'))
    <ul>
        @forelse($items as $id => $qty)
            @php($product = $products[$id] ?? null)
            @if($product)
            <li>
                {{ $product->name }} x {{ $qty }} — ${{ number_format($product->price * $qty, 2) }}
                <form method="POST" action="{{ route('cart.remove', $id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Quitar</button>
                </form>
            </li>
            @endif
        @empty
            <li>Tu carrito está vacío.</li>
        @endforelse
    </ul>
    @php($total = $items->reduce(fn($carry, $qty, $id) => $carry + (($products[$id]->price ?? 0) * $qty), 0))
    <p><strong>Total: ${{ number_format($total, 2) }}</strong></p>

    <div style="margin-top:12px; display:flex; gap:12px;">
        <a href="{{ url('/') }}">Seguir comprando</a>
        @if($total > 0)
        <a href="{{ route('checkout.show') }}" class="button">Ir a pagar</a>
        @endif
    </div>
</div>
@endsection
