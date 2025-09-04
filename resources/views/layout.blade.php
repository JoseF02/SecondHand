<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecondHand</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="announcement">👀 Estate atenta: ingresos nuevos todos los días! ✨</div>
    <header class="site-header">
        <a href="{{ route('products.index') }}"><h2>SecondHand</h2></a>
        <nav>
            <a href="{{ route('products.index') }}">Comprar todo</a>
            <a href="{{ route('products.index', ['category' => request('category')]) }}">Novedades</a>
            <a href="{{ route('cart.index') }}">Carrito</a>
        </nav>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <h3>Categorías</h3>
            <ul>
                <li><a href="{{ route('products.index') }}">Todas</a></li>
                @foreach($sharedCategories as $cat)
                    <li><a href="{{ route('products.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </aside>
        <main>
            @yield('content')
        </main>
    </div>

    <footer class="site-footer">
        © {{ date('Y') }} SecondHand · Políticas · Términos
    </footer>
</body>
</html>
