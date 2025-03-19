<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show the signup form
    public function showSignupForm()
    {
        if (Auth::check()) {
            return redirect()->route('seekers.index'); // Redirect to seekers index if logged in
        }
        
        return view('pages.signup');
    }

    // Handle signup
    public function signup(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_fname' => 'required|string|max:255',
            'user_lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Check validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create a new user with a default role
        User::create([
            'user_fname' => $request->user_fname,
            'user_lname' => $request->user_lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => 'missionary', // Set default role
        ]);

        // Redirect or return response
        return redirect()->route('auth.login')->with('success', 'Registration successful! You can now log in.');
    }

    // Show the login form
    public function showLoginForm()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            return redirect()->route('seekers.index'); // Redirect to seekers index if logged in
        }

        return view('pages.login'); // Ensure this view exists
    }


    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Find user
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'User not found.');
        }
    
        // ✅ Convert `$2a$` to `$2y$` before checking the hash
        $hashedPassword = str_replace('$2a$', '$2y$', $user->password);
    
        // ✅ Check if the password matches the hash
        if (!Hash::check($request->password, $hashedPassword)) {
            return redirect()->route('auth.login')->with('error', 'Invalid credentials.');
        }
    
        // ✅ If password is `$2a$`, rehash it as `$2y$` for Laravel compatibility
        if (str_starts_with($user->password, '$2a$') && Hash::needsRehash($user->password)) {
            $user->update(['password' => Hash::make($request->password)]);
        }
    
        Auth::login($user);
        return redirect()->intended('/');
    }        

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'Logged out successfully!');
    }
}
