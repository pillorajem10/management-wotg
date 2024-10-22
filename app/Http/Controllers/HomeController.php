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
        $user = Auth::user();
        $blogCount = Blog::count();
        $seekerCount = Seeker::count();
        $userCount = User::where('user_role', 'missionary')->count();
    
        // Fetch verse of the day
        $verseOfTheDay = $this->getVerseOfTheDay();
    
        return view('pages.home', compact('user', 'blogCount', 'seekerCount', 'userCount', 'verseOfTheDay'));
    }
    
    private function getVerseOfTheDay()
    {
        $response = file_get_contents('https://bible-api.com/?random=verse'); // Replace with the correct endpoint
        return json_decode($response);
    }
       
}
