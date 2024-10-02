<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;

class UserManagementController extends Controller
{
    public function index()
{
    // Fetch all users with their products and the last login from tokens
    $users = User::with(['products', 'tokens'])->get();

    // Count total and active users
    $totalUsers = User::count();
    $activeUsers = User::where('user_type', '!=', 'inactive')->count();

    return view('user-management', compact('users', 'totalUsers', 'activeUsers'));
}

public function deactivate($id)
{
    $user = User::find($id);
    if ($user) {
        $user->user_type = 'inactive';
        $user->save();

        // Return with a status message to trigger the pop-up
        return response()->json([
            'status' => 'User deactivated successfully',
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}

    // Reactivate user account (same as before)
    public function reactivate($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->user_type = 'user';
            $user->save();
            return redirect()->route('userManagement')->with('status', 'User reactivated successfully.');
        }
    }

    // Delete user product (same as before)
    public function deleteUserProduct($productId)
{
    $product = Product::find($productId);
    if ($product) {
        $product->delete();
    }
    return redirect()->route('userManagement')->with('status', 'Product deleted successfully.');
}
    

    // Delete user (same as before)
    public function deleteUser($id)
{
    $user = User::find($id);
    if ($user) {
        // Optionally delete all products of the user
        $user->products()->delete();
        $user->delete();
    }
    return redirect()->route('userManagement')->with('status', 'User deleted successfully.');
}
}
