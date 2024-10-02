<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Get all products
    public function index()
    {
        return Product::all();
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'required',
            'description' => 'nullable',
            'images' => 'nullable', // Make sure images field is optional
        ]);

        // Retrieve the last flutter-token from the personal_access_tokens table
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        // If a token exists, get the tokenable_id (user_id)
        $userId = $lastToken ? $lastToken->tokenable_id : null;

        // Check if userId is valid
        if (!$userId) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Handle the image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Store each image and get its path
                $path = $image->store('uploads/products', 'public');
                $imagePaths[] = $path;
            }
        }

        // Create the product with user_id and other attributes
        $product = Product::create([
            'user_id' => $userId, // Store the user_id from the token
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
            'description' => $request->description,
            'images' => json_encode($imagePaths), // Store the image paths as JSON
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    // Get products for the authenticated user using the last personal access token
    public function getUserProducts(Request $request)
    {
        // Retrieve the last 'custom-token' from the personal_access_tokens table
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc') // Get the latest token
            ->first();

        // If no token exists, return unauthorized response
        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the user associated with the token
        $userId = $lastToken->tokenable_id;
        $products = Product::where('user_id', $userId)->get(); // Fetch products belonging to the logged-in user

        return response()->json($products);
    }

    // Mark a product as paid
    public function markAsPaid($id)
    {
        // Find the product by its ID
        $product = Product::find($id);

        if ($product) {
            // Update the payment status to 'paid'
            $product->payment_status = 'paid';
            $product->save();

            return response()->json(['message' => 'Product marked as paid'], 200);
        }

        // If the product is not found
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Update product status
    public function updateStatus(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->status = $request->status; // Update the status (pending, approved, declined)
            $product->save();

            return response()->json([
                'message' => 'Product status updated successfully',
                'product' => $product,
            ]);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }

    // Get a single product by ID
    public function show($id)
    {
        // Load product with user information
        $product = Product::with('user')->find($id);

        if ($product) {
            return response()->json($product);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }

    // Update an existing product with images
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $lastToken->tokenable_id;
        $product = Product::find($id);

        // Ensure that the product belongs to the authenticated user
        if (!$product || $product->user_id != $userId) {
            return response()->json(['message' => 'Product not found or unauthorized'], 404);
        }

        // Handle the image uploads
        if ($request->hasFile('images')) {
            foreach (json_decode($product->images, true) as $image) {
                Storage::disk('public')->delete($image); // Delete old images
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/products', 'public');
                $imagePaths[] = $path;
            }
            $product->images = json_encode($imagePaths);
        }

        // Update the product fields
        $product->name = $request->input('name');
        $product->brand = $request->input('brand');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->category = $request->input('category');
        $product->description = $request->input('description');

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }
    // Delete a product along with its images
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            // Optionally delete images from storage
            if ($product->images) {
                foreach (json_decode($product->images) as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }
}
