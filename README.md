# SecondHand

Aplicación demo de e-commerce para ropa de segunda mano construida con Laravel y PostgreSQL.

## Configuración

1. Instalar dependencias:
   ```bash
   composer install
   ```
2. Copiar `.env.example` a `.env` y ajustar credenciales de la base de datos.
3. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
4. Levantar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

## Características

- Listado de productos de ropa usados.
- Carrito de compras gestionado en la sesión.

## Licencia

MIT
