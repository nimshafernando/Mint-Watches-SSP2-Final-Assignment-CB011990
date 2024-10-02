<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    // Add a review for a product
    public function addReview(Request $request, $productId)
    {
        $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5', // Rating should be between 1 and 5
        ]);

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

        // Create the review with pending status
        $review = Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
            'review_status' => 'pending', // Initially set the status as pending
        ]);

        return response()->json(['message' => 'Review submitted successfully and awaiting approval', 'review' => $review], 201);
    }

    // Get approved reviews for a product
    public function getApprovedReviews($productId)
    {
        $reviews = Review::with('user')
            ->where('product_id', $productId)
            ->where('review_status', 'approved')
            ->get();

        return response()->json($reviews, 200);
    }

    // Approve a review
    public function approveReview($id)
    {
        $review = Review::find($id);

        if ($review) {
            $review->review_status = 'approved';
            $review->save();

            return response()->json(['message' => 'Review approved successfully', 'review' => $review], 200);
        }

        return response()->json(['message' => 'Review not found'], 404);
    }
}
