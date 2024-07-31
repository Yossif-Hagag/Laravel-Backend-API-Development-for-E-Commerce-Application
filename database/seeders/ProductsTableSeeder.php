<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('products')->insert([
        //     [
        //         'name' => 'Product 1',
        //         'description' => 'Description for product 1',
        //         'price' => 99.99,
        //         'quantity' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'name' => 'Product 2',
        //         'description' => 'Description for product 2',
        //         'price' => 49.99,
        //         'quantity' => 20,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ]);
        Product::factory(300)->create();
    }
}
