<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            [
                'name' => 'Camisa vintage',
                'description' => 'Camisa de algodón de segunda mano en buen estado.',
                'price' => 15.00,
                'image' => 'camisa.jpg',
            ],
            [
                'name' => 'Pantalón clásico',
                'description' => 'Pantalón de jean reciclado.',
                'price' => 20.00,
                'image' => 'pantalon.jpg',
            ],
            [
                'name' => 'Chaqueta de cuero',
                'description' => 'Chaqueta de cuero de segunda mano.',
                'price' => 45.00,
                'image' => 'chaqueta.jpg',
            ],
        ]);
    }
}
