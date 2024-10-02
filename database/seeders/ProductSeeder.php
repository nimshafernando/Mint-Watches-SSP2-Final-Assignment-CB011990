<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Generate 8 products with specific watch categories
        $categories = [
            'Dress Watches', 'Diver Watches', 'Chronograph Watches', 
            'Pilot Watches', 'Smartwatches', 'Luxury Watches', 
            'Sports Watches', 'Tactical Watches'
        ];

        foreach ($categories as $index => $category) {
            Product::create([
                'name' => 'Watch ' . ($index + 1),
                'brand' => 'Brand ' . ($index + 1),
                'price' => rand(100, 1000), // Random price between 100 and 1000
                'stock' => rand(1, 50), // Random stock quantity
                'category' => $category,
                'payment_status' => 'unpaid',
                'description' => 'A high-quality ' . $category,
                'images' => json_encode([]), // Empty images
                'image_path' => '', // No images
                'user_id' => rand(1, 10), // Assuming you have user IDs between 1 and 10
                'status' => 'pending', // Random status
            ]);
        }
    }
}
