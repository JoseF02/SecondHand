@extends('layout')

@section('content')
<div class="container">
    <h1>Productos</h1>
    <ul>
        @foreach($products as $product)
            <li>
                <strong>{{ $product->name }}</strong> - ${{ number_format($product->price, 2) }}
                <form method="POST" action="{{ route('cart.add', $product) }}" style="display:inline">
                    @csrf
                    <button type="submit">Agregar</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
