<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    // Toggle favorite status for a product
    public function toggleFavorite(Request $request, $productId)
    {
        // Retrieve the logged-in user from the last token
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $lastToken->tokenable_id;

        // Check if the product is already a favorite
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            // Unmark as favorite if already marked
            $favorite->delete();
            return response()->json(['message' => 'Removed from favorites']);
        }

        // Mark as favorite if not already marked
        Favorite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['message' => 'Marked as favorite']);
    }

    // Get all favorites for a user
    public function getFavorites(Request $request)
    {
        // Retrieve the logged-in user from the last token
        $lastToken = DB::table('personal_access_tokens')
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $lastToken->tokenable_id;

        // Retrieve all favorite products for the user
        $favorites = Favorite::where('user_id', $userId)->with('product')->get();

        return response()->json($favorites);
    }
}
