<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Show products for the user associated with the last custom-token
    public function index(Request $request)
    {
        // Retrieve the last 'custom-token' from the personal_access_tokens table
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        // If no token exists, return unauthorized response
        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the user associated with the token
        $userId = $lastToken->tokenable_id;
        $user = User::find($userId);

        // If the user is not found, return a 404 response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the products and payment status for this user
        $products = Product::where('user_id', $userId)
            ->with('payment') // Assuming the Product model has a `payment` relationship
            ->get();

        return response()->json($products, 200);
    }

    // Mark product payment as paid for the user associated with the last custom-token
    public function markAsPaid(Request $request, $productId)
    {
        // Retrieve the last 'custom-token' from the personal_access_tokens table
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        // If no token exists, return unauthorized response
        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the user associated with the token
        $userId = $lastToken->tokenable_id;
        $user = User::find($userId);

        // If the user is not found, return a 404 response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Find the payment entry for this product
        $payment = Payment::where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Mark the payment as paid
        $payment->payment_status = 'paid';
        $payment->save();

        return response()->json(['message' => 'Payment marked as paid'], 200);
    }
}
