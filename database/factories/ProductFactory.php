<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Generate exactly 8 products using the ProductFactory
        Product::factory()->count(8)->create();
    }
}
