<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    public function index()
    {
        $blogs = Blog::all(); // Fetch all blogs
        return view('pages.blogs', compact('blogs'));
    }

    // Show the details of a specific blog
    public function show($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        return view('pages.blogDetails', compact('blog'));
    }

    // Show the form for creating a new blog
    public function create()
    {
        return view('pages.addBlog');
    }

    // Store a newly created blog in storage
    public function store(Request $request)
    {
        $request->validate([
            'blog_title' => 'required|string|max:255',
            'blog_body' => 'required|string',
            'blog_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
    
        // Handle the image upload if there's a thumbnail
        $thumbnail = null;
        if ($request->hasFile('blog_thumbnail')) {
            $thumbnail = file_get_contents($request->file('blog_thumbnail')->getRealPath()); // Get binary content
        }
    
        Blog::create([
            'blog_title' => $request->blog_title,
            'blog_body' => $request->blog_body,
            'blog_thumbnail' => $thumbnail, // Store binary in longblob
            'blog_creator' => auth()->id(), // Assuming user is logged in
        ]);
    
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }    
}
