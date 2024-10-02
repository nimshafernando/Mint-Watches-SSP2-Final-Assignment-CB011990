<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard method to show pending, approved, and declined products
    public function dashboard()
    {
        $pendingProducts = Product::where('status', 'pending')->get();
        $approvedProducts = Product::where('status', 'approved')->get();
        $declinedProducts = Product::where('status', 'declined')->get();

        return view('dashboard', compact('pendingProducts', 'approvedProducts', 'declinedProducts'));
    }

    // Approve a product
    public function approve($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 'approved';
            $product->save();
        }

        return redirect()->route('dashboard')->with('status', 'Product approved successfully.');
    }

    // Decline a product
    public function decline($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 'declined';
            $product->save();
        }

        return redirect()->route('dashboard')->with('status', 'Product declined successfully.');
    }

    // Analytics page method to show various product-related statistics
    public function analytics()
{
    // Product status analytics
    $pendingProducts = Product::where('status', 'pending')->count();
    $approvedProducts = Product::where('status', 'approved')->count();
    $declinedProducts = Product::where('status', 'declined')->count();

    // Product category distribution
    $categoryDistribution = Product::select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->pluck('count', 'category');

    // Most listed brands
    $mostListedBrands = Product::select('brand', DB::raw('COUNT(*) as count'))
        ->groupBy('brand')
        ->pluck('count', 'brand');

    // Monthly listings growth
    $monthlyListingsGrowth = Product::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
        ->groupBy('month')
        ->pluck('count', 'month');

    // Average price by category
    $avgPriceByCategory = Product::select('category', DB::raw('AVG(price) as avg_price'))
        ->groupBy('category')
        ->pluck('avg_price', 'category');

    // User location distribution
    $locationDistribution = User::select('location', DB::raw('COUNT(*) as count'))
        ->groupBy('location')
        ->pluck('count', 'location');

    // Top 5 product categories
    $topCategories = Product::select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->orderBy('count', 'desc')
        ->take(5)
        ->pluck('count', 'category');

    // Total sales by month
    $totalSalesByMonth = Product::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(price) as total_sales'))
        ->groupBy('month')
        ->pluck('total_sales', 'month');

    // Average price by brand
    $avgPriceByBrand = Product::select('brand', DB::raw('AVG(price) as avg_price'))
        ->groupBy('brand')
        ->pluck('avg_price', 'brand');

    // Product listings over time
    $productListingsOverTime = Product::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
        ->groupBy('date')
        ->pluck('count', 'date');

    // New analytics: Customer total revenue earned
    $customerRevenue = User::join('products', 'users.id', '=', 'products.user_id')
        ->where('products.status', 'approved')
        ->select('users.name', DB::raw('SUM(products.price) as total_revenue'))
        ->groupBy('users.name')
        ->pluck('total_revenue', 'users.name')
        ->toArray();  // Ensure it returns an array even if empty

    // New analytics: Paid and Unpaid orders by month
    $productOrdersByMonth = Product::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
        ->groupBy('month')
        ->pluck('count', 'month');

    $paidOrders = Product::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
        ->where('payment_status', 'paid')
        ->groupBy('month')
        ->pluck('count', 'month');

    $unpaidOrders = Product::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
        ->where('payment_status', 'unpaid')
        ->groupBy('month')
        ->pluck('count', 'month');

    // Return all data to the view
    return view('analytics', compact(
        'pendingProducts',
        'approvedProducts',
        'declinedProducts',
        'categoryDistribution',
        'mostListedBrands',
        'monthlyListingsGrowth',
        'avgPriceByCategory',
        'locationDistribution',
        'topCategories',
        'totalSalesByMonth',
        'avgPriceByBrand',
        'productListingsOverTime',
        'customerRevenue',  // Ensure this is passed to the view
        'productOrdersByMonth',
        'paidOrders',
        'unpaidOrders'
    ));
}

}
