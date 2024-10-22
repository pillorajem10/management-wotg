<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;   // Import Blog model
use App\Models\Seeker; // Import Seeker model
use App\Models\User;   // Import User model

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $blogCount = Blog::count(); // Count of blogs
        $seekerCount = Seeker::count(); // Count of seekers
        $userCount = User::where('user_role', 'missionary')->count(); // Count of users with role "missionary"
    
        return view('pages.home', compact('user', 'blogCount', 'seekerCount', 'userCount')); // Pass the counts to the view
    }    
}
