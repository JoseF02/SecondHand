@extends('layout')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    @if($product->image)
        <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width:400px; width:100%; height:auto; border-radius:8px;">
    @endif
    <p>{{ $product->description }}</p>
    <p><strong>${{ number_format($product->price, 2) }}</strong></p>

    <form method="POST" action="{{ route('cart.add', $product) }}" style="display:inline">
        @csrf
        <button type="submit">Agregar al carrito</button>
    </form>

    <a href="{{ route('cart.index') }}" style="margin-left:12px">Ver carrito</a>
</div>
@endsection


