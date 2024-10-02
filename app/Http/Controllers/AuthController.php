<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Register method (includes user_type)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:Buyer,Seller',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate profile image
        ]);
    
        // Handle profile image upload
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public'); // Store image in public disk
        }
    
        // Create user with user_type and profile_image
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'profile_image' => $imagePath, // Store image path
        ]);
    
        return response()->json(['message' => 'User registered successfully!', 'user' => $user]);
    }
    

    // Login method (email and password only)
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Generate a token for login
            $loginToken = $user->createToken('login-token')->plainTextToken;

            // Generate a second token for a specific purpose (e.g., custom-token)
            $customToken = $user->createToken('custom-token')->plainTextToken;

            // Store both tokens in the response
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'login_token' => $loginToken,
                'custom_token' => $customToken, // Add the custom token to the response
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 500);
        }
    }

    // Retrieve the most recent custom token for a user
    public function getLastCustomToken($userId)
    {
        // Get the latest custom token for the user
        $lastCustomToken = DB::table('personal_access_tokens')
            ->where('tokenable_id', $userId)
            ->where('name', 'custom-token')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCustomToken) {
            return response()->json([
                'custom_token' => $lastCustomToken->token,
            ]);
        } else {
            return response()->json([
                'message' => 'No custom token found for the user',
            ], 404);
        }
    }

    // Logout method
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
