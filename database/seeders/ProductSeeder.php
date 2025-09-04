<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect(['Camisas', 'Pantalones', 'Abrigos'])->map(function ($name) {
            return \App\Models\Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        })->keyBy('name');

        \App\Models\Product::insert([
            [
                'name' => 'Camisa vintage',
                'description' => 'Camisa de algodón de segunda mano en buen estado.',
                'price' => 15.00,
                'image' => 'camisa.jpg',
                'category_id' => $categories['Camisas']->id ?? null,
            ],
            [
                'name' => 'Pantalón clásico',
                'description' => 'Pantalón de jean reciclado.',
                'price' => 20.00,
                'image' => 'pantalon.jpg',
                'category_id' => $categories['Pantalones']->id ?? null,
            ],
            [
                'name' => 'Chaqueta de cuero',
                'description' => 'Chaqueta de cuero de segunda mano.',
                'price' => 45.00,
                'image' => 'chaqueta.jpg',
                'category_id' => $categories['Abrigos']->id ?? null,
            ],
        ]);
    }
}
