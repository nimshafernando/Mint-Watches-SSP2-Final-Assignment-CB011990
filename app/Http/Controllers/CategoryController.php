<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    // Show the Category Management page
    public function index()
    {
        // Get all unique categories from the product table
        $categories = Product::select('category')->distinct()->get();
        return view('categoryManagement', compact('categories'));
    }

    // Add a new category
    public function addCategory(Request $request)
    {
        // Validate the new category
        $request->validate([
            'category' => 'required|string|max:255|unique:products,category',
        ]);

        // Create a product with the new category (without adding full product details)
        Product::create([
            'name' => 'Temporary Product',
            'brand' => 'Temp',
            'price' => 0,
            'stock' => 0,
            'category' => $request->category,
            'description' => 'Temporary Description',
            'user_id' => 1, 
            'status' => 'pending',
        ]);

        return redirect()->route('categoryManagement')->with('success', 'Category added successfully.');
    }

    // Remove a category
    public function removeCategory($category)
    {
        // Remove products associated with the category
        Product::where('category', $category)->delete();

        return redirect()->route('categoryManagement')->with('success', 'Category removed successfully.');
    }
}
