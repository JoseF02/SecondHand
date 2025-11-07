<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'cart_data',
        'total',
        'status',
        'payment_method',
        'payment_id',
        'payment_status',
        'billing_info',
        'shipping_info',
        'notes',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'billing_info' => 'array',
        'shipping_info' => 'array',
        'total' => 'decimal:2',
    ];

    // Relación con el usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Generar número de orden único
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(uniqid());
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    // Obtener productos de la orden
    public function getProducts()
    {
        if (!$this->cart_data) {
            return collect();
        }

        $productIds = array_keys($this->cart_data);
        return Product::whereIn('id', $productIds)->get()->keyBy('id');
    }

    // Verificar si la orden está pagada
    public function isPaid(): bool
    {
        return in_array($this->status, ['completed', 'processing']);
    }

    // Verificar si la orden puede ser cancelada
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    // Estados disponibles
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pendiente',
            'processing' => 'Procesando',
            'completed' => 'Completada',
            'failed' => 'Fallida',
            'cancelled' => 'Cancelada',
            'refunded' => 'Reembolsada',
        ];
    }
}
