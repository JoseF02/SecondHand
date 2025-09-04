@extends('layout')

@section('content')
<div class="container">
    <h1>¡Gracias por tu compra!</h1>
    <p>Tu pedido ha sido procesado. Te enviaremos los detalles por email.</p>
    <a href="{{ route('products.index') }}">Volver a la tienda</a>
</div>
@endsection


