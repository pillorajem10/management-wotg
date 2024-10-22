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


    // Handle login
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Prepare credentials for authentication
        $credentials = $request->only('email', 'password');
    
        \Log::info('Attempting login for: ', $credentials);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Authentication passed, redirect to intended page
            return redirect()->intended('/');
        }
    
        // If login fails
        return redirect()->route('auth.login')->with('error', 'The credentials you provided do not match our records.');
    }            

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'Logged out successfully!');
    }
}
