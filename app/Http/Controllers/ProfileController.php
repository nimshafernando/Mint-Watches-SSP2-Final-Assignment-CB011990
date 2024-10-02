<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Show profile for the user associated with the last custom-token
    public function show(Request $request)
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

        // Return the user profile details
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'bio' => $user->bio,
            'location' => $user->location,
            'profile_photo_path' => $user->profile_photo_path ?? '', // Optional field for profile photo
        ], 200);
    }

    // Update profile for the user associated with the last custom-token
    public function update(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'nullable|string',
            'bio' => 'nullable|string',
            'location' => 'nullable|string',
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
        $user = User::find($userId);

        // If the user is not found, return a 404 response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update the user's profile information
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->bio = $request->input('bio');
        $user->location = $request->input('location');

        // Save the updated user data
        $user->save();

        // Return the updated user profile
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }
}