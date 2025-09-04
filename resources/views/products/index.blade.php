@extends('layout')

@section('content')
<div class="container">
    <div class="hero">
        <h1>New in</h1>
        <p>Ingresos nuevos todas las semanas</p>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="filters">
        <div>
            <label>Buscar</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nombre o descripción">
        </div>
        <div>
            <label>Mín</label>
            <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" style="width:120px;">
        </div>
        <div>
            <label>Máx</label>
            <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" style="width:120px;">
        </div>
        <div>
            <label>Orden</label>
            <select name="sort">
                <option value="" {{ request('sort')==='' ? 'selected' : '' }}>Recientes</option>
                <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
            </select>
        </div>
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <button type="submit">Filtrar</button>
        <a href="{{ route('products.index') }}">Limpiar</a>
    </form>

    <div class="products-grid">
        @forelse($products as $product)
            <div class="card">
                <a href="{{ route('products.show', $product) }}">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    @else
                        <div class="media"></div>
                    @endif
                </a>
                <div class="content">
                    <h3>{{ $product->name }}</h3>
                    @if($product->category)
                        <small>{{ $product->category->name }}</small>
                    @endif
                    <p class="price">${{ number_format($product->price, 2) }}</p>
                    <form method="POST" action="{{ route('cart.add', $product) }}">
                        @csrf
                        <button type="submit">Agregar</button>
                    </form>
                </div>
            </div>
        @empty
            <p>No hay productos que coincidan con los filtros.</p>
        @endforelse
    </div>

    <div style="margin-top:16px;">
        {{ $products->links() }}
    </div>

    <div class="brands">
        <h3>Comprá por marca</h3>
        <div class="brands-row">
            <div class="brand-pill">Nike</div>
            <div class="brand-pill">Adidas</div>
            <div class="brand-pill">Mango</div>
            <div class="brand-pill">H&M</div>
            <div class="brand-pill">Zara</div>
            <div class="brand-pill">Levi's</div>
        </div>
    </div>
</div>
@endsection
