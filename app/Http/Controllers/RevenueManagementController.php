<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueManagementController extends Controller
{
    // Show Revenue Management page
    public function index()
{
    // Fetch products with relevant revenue details
    $products = Product::with('user')
        ->select('id', 'name', 'brand', 'price', 'stock', 'category', 'payment_status', 'description', 'user_id', 'status')
        ->get();

    // Calculate total revenue per user (seller)
    $userRevenues = User::join('products', 'users.id', '=', 'products.user_id')
        ->where('products.payment_status', 'paid')  // Only consider paid products
        ->select('users.name', DB::raw('SUM(products.price) as total_revenue'))
        ->groupBy('users.name')
        ->pluck('total_revenue', 'users.name');

    // Available payment statuses
    $paymentStatuses = ['paid', 'unpaid'];

    // Pass the variables to the view
    return view('RevenueManagement', compact('products', 'userRevenues', 'paymentStatuses'));
}


    // Update payment status of a product
    public function updatePaymentStatus(Request $request, $id)
    {
        // Validate the payment status
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        // Find the product and update the payment status
        $product = Product::findOrFail($id);
        $product->payment_status = $request->input('payment_status');
        $product->save();

        return redirect()->route('revenue.management')->with('success', 'Payment status updated successfully.');
    }
}
