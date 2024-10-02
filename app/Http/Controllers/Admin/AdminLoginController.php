<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');  // Display the admin login form
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Ensure that the user is an admin (is_admin = 1)
            if ($user->is_admin == 1) {
                return redirect()->route('/dashboard');
            }

            // Logout and redirect if user is not an admin
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'You do not have admin access.',
            ]);
        }

        return redirect()->back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }
}
