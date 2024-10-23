<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
// use Carbon\Carbon; 
// use Illuminate\Support\Facades\Log; 

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    /*
    public function index()
    {
        // Set the timezone to Manila
        $today = Carbon::now('Asia/Manila');
    
        // Log the current date
        Log::info('Fetching blogs on date: ' . $today->toDateTimeString());
    
        // Fetch all blogs with a release date less than or equal to today
        $blogs = Blog::where('blog_release_date_and_time', '<=', $today)->get();
    
        return view('pages.blogs', compact('blogs'));
    }
    */


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
            'blog_intro' => 'required|string|max:255', // Validate blog_intro
            'blog_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            'blog_release_date_and_time' => 'nullable|date', // Validate the release date and time
        ]);
    
        // Handle the image upload if there's a thumbnail
        $thumbnail = null;
        if ($request->hasFile('blog_thumbnail')) {
            $thumbnail = file_get_contents($request->file('blog_thumbnail')->getRealPath()); // Get binary content
        }
    
        Blog::create([
            'blog_title' => $request->blog_title,
            'blog_body' => $request->blog_body,
            'blog_intro' => $request->blog_intro, // Include blog_intro
            'blog_thumbnail' => $thumbnail, // Store binary in longblob
            'blog_creator' => auth()->id(), // Assuming user is logged in
            'blog_is_hidden' => true, // Set default to true
            'blog_release_date_and_time' => $request->blog_release_date_and_time, // Save release date and time
        ]);
    
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }       
    
    // Show the form for editing a specific blog
    public function edit($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        return view('pages.editBlog', compact('blog')); // Pass the blog data to the view
    }

    // Update the specified blog in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_title' => 'required|string|max:255',
            'blog_body' => 'required|string',
            'blog_intro' => 'required|string|max:255', // Validate blog_intro
            'blog_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
    
        // Find the blog and update its details
        $blog = Blog::findOrFail($id);
        $blog->blog_title = $request->blog_title;
        $blog->blog_body = $request->blog_body;
        $blog->blog_intro = $request->blog_intro; // Update blog_intro
    
        // Handle the image upload if there's a new thumbnail
        if ($request->hasFile('blog_thumbnail')) {
            $blog->blog_thumbnail = file_get_contents($request->file('blog_thumbnail')->getRealPath()); // Update with new thumbnail
        }
    
        // Set blog_approved to true if the auth user is owner
        if (auth()->user()->user_role === 'owner') {
            $blog->blog_approved = true;
        }
    
        $blog->save(); // Save the changes
    
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }      

    // Delete the specified blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        $blog->delete(); // Delete the blog

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }

    public function approve($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        $blog->blog_approved = !$blog->blog_approved; // Toggle approval status
        $blog->save(); // Save the changes

        return redirect()->route('blogs.index')->with('success', 'Blog approval status updated successfully!');
    }

}
