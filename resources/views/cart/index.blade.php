@extends('layout')

@section('content')
<div class="container">
    <h1>Carrito</h1>
    <ul>
        @forelse($cart as $id => $qty)
            <li>{{ \App\Models\Product::find($id)->name }} x {{ $qty }}
                <form method="POST" action="{{ route('cart.remove', $id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Quitar</button>
                </form>
            </li>
        @empty
            <li>Tu carrito está vacío.</li>
        @endforelse
    </ul>
    <a href="{{ url('/') }}">Seguir comprando</a>
</div>
@endsection
