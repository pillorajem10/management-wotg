<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Seeker;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Mail\DailyBlogReport; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;


class BlogController extends Controller
{
    private function clearNodeCache() {
        $nodeApiUrl = env('NODE_API_URL', 'http://localhost:5000/blogs/clear-blog-cache');
    
        try {
            $response = Http::post($nodeApiUrl);
            \Log::info('✅ Node.js cache cleared successfully.');
        } catch (\Exception $e) {
            \Log::error('❌ Failed to clear Node.js cache: ' . $e->getMessage());
        }
    }

    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');
        
        // Store the search term in the session
        session(['blog_search_term' => $search]);
    
        // Get the current page from the request, default to 1 if not set
        $currentPage = $request->input('page', 1);
    
        // Store the current page in the session
        session(['blog_current_page' => $currentPage]);
    
        // Fetch blogs with pagination, applying the search filter if provided
        $blogs = Blog::when($search, function ($query, $search) {
            return $query->where('blog_title', 'like', '%' . $search . '%');
        })->paginate(5);
    
        return view('pages.blogs', compact('blogs', 'search'));
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
            'blog_intro' => 'required|string',
            'blog_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_release_date_and_time' => 'nullable|date|unique:blogs,blog_release_date_and_time',
        ]);
    
        // Handle the image upload if there's a thumbnail
        $thumbnailName = null;
        if ($request->hasFile('blog_thumbnail')) {
            $file = $request->file('blog_thumbnail');
    
            // Generate a unique WebP filename
            $thumbnailName = time() . '.webp';
    
            // Define paths for Laravel & Node.js based on environment
            if (App::environment('production')) {
                $nodePath = '/var/www/community.wotgonline.com/wotg-social-api/uploads';
            } else {
                $nodePath = 'C:/Users/pc/projects/wotg-social-api/uploads';
            }
            $laravelPath = public_path('uploads');
    
            // Ensure directories exist
            if (!file_exists($laravelPath)) {
                mkdir($laravelPath, 0777, true);
            }
            if (!file_exists($nodePath)) {
                mkdir($nodePath, 0777, true);
            }
    
            // ✅ Convert image to WebP using GD Library
            $imagePath = $file->getPathname();
            $imageType = exif_imagetype($imagePath); // Get image type
    
            // Create image resource from the uploaded file
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($imagePath);
                    imagepalettetotruecolor($image); // Convert PNG palette to true color
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($imagePath);
                    break;
                default:
                    return redirect()->route('blogs.index')->with('error', 'Invalid image format.');
            }
    
            // Save the converted WebP image
            imagewebp($image, $laravelPath . '/' . $thumbnailName, 90);
            imagedestroy($image); // Free memory
    
            // Copy WebP file to Node.js backend folder
            copy($laravelPath . '/' . $thumbnailName, $nodePath . '/' . $thumbnailName);
        }
    
        // Store only the WebP filename in the database
        Blog::create([
            'blog_title' => $request->blog_title,
            'blog_body' => $request->blog_body,
            'blog_intro' => $request->blog_intro,
            'blog_thumbnail' => $thumbnailName,
            'blog_creator' => auth()->id(),
            'blog_is_hidden' => true,
            'blog_release_date_and_time' => $request->blog_release_date_and_time,
        ]);

        $this->clearNodeCache(); // ✅ Clear Node.js Cache

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }
          
    
    // Show the form for editing a specific blog
    public function edit($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        return view('pages.editBlog', compact('blog')); // Pass the blog data to the view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_title' => 'required|string|max:255',
            'blog_body' => 'required|string',
            'blog_intro' => 'required|string',
            'blog_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_release_date_and_time' => 'required|date|unique:blogs,blog_release_date_and_time,' . $id,
        ], [
            'blog_release_date_and_time.unique' => 'The Date you chose to release this blog is already taken. Please choose a different date and time.',
        ]);
    
        // Find the blog and update its details
        $blog = Blog::findOrFail($id);
        $blog->blog_title = $request->blog_title;
        $blog->blog_body = $request->blog_body;
        $blog->blog_intro = $request->blog_intro;
    
        // Handle the image upload if there's a new thumbnail
        if ($request->hasFile('blog_thumbnail')) {
            $file = $request->file('blog_thumbnail');
    
            // Generate a unique WebP filename
            $thumbnailName = time() . '.webp';
    
            // Define paths based on environment
            $laravelPath = public_path('uploads'); // Laravel's public uploads folder
            $nodePath = App::environment('production') 
                ? '/var/www/community.wotgonline.com/wotg-social-api/uploads'  // Production path
                : 'C:/Users/pc/projects/wotg-social-api/uploads'; // Local path
    
            // Ensure both directories exist
            if (!file_exists($laravelPath)) {
                mkdir($laravelPath, 0777, true);
            }
            if (!file_exists($nodePath)) {
                mkdir($nodePath, 0777, true);
            }
    
            // ✅ Convert image to WebP using GD Library
            $imagePath = $file->getPathname();
            $imageType = exif_imagetype($imagePath); // Get image type
    
            // Create image resource from the uploaded file
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($imagePath);
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($imagePath);
                    break;
                default:
                    return redirect()->route('blogs.index')->with('error', 'Invalid image format.');
            }
    
            // Save the converted WebP image
            imagewebp($image, $laravelPath . '/' . $thumbnailName, 90);
            imagedestroy($image); // Free memory
    
            // Copy WebP file to Node.js backend folder
            copy($laravelPath . '/' . $thumbnailName, $nodePath . '/' . $thumbnailName);
    
            // Store only the filename
            $blog->blog_thumbnail = $thumbnailName;
        }
    
        // Update the release date and time
        $blog->blog_release_date_and_time = $request->blog_release_date_and_time;
    
        // Set blog_approved to true if the auth user is owner
        if (auth()->user()->user_role === 'owner') {
            $blog->blog_approved = true;
        }
    
        $blog->save();
        $this->clearNodeCache(); // ✅ Clear Node.js Cache
    
        return redirect()->route('blogs.index', [
            'search' => session('blog_search_term', ''),
            'page' => session('blog_current_page', 1)
        ])->with('success', 'Blog updated successfully!');
    }
    
             

    // Delete the specified blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        $blog->delete(); // Delete the blog

        $this->clearNodeCache(); // ✅ Clear Node.js Cache

        return redirect()->route('blogs.index', [
            'search' => session('blog_search_term', ''),
            'page' => session('blog_current_page', 1)
        ])->with('success', 'Blog updated successfully!');        
    }

    public function approve($id)
    {
        $blog = Blog::findOrFail($id); // Fetch the specific blog or fail if not found
        $blog->blog_approved = !$blog->blog_approved; // Toggle approval status
        $blog->save(); // Save the changes

        $this->clearNodeCache(); // ✅ Clear Node.js Cache

        return redirect()->route('blogs.index', [
            'search' => session('blog_search_term', ''),
            'page' => session('blog_current_page', 1)
        ])->with('success', 'Blog updated successfully!');
    }
    
    public function sendDailyEmail()
    {
        $this->sendEmails(Seeker::class, 'seeker_email', 'seeker_fname', 50); // Send in batches of 50
    }
    
    public function sendDailyEmailUsers()
    {
        $this->sendEmails(User::class, 'email', 'user_fname', 50); // Send in batches of 50
    }

    public function sendDailyEmailSubs()
    {
        $this->sendEmails(Subscriber::class, 'sub_email', 'sub_fname', 50); // Send in batches of 50
    }
    
    private function sendEmails(string $modelClass, string $emailField, string $nameField, int $batchSize)
    {
        $today = Carbon::now('Asia/Manila')->format('Y-m-d');
        $blogs = Blog::whereDate('blog_release_date_and_time', $today)
                     ->where('blog_approved', true)
                     ->get();
    
        \Log::info('Number of approved blogs for today: ' . $blogs->count());
    
        if ($blogs->isNotEmpty()) {
            foreach ($blogs as $blog) {
                // Fetch all recipients
                $recipients = $modelClass::select($emailField, $nameField)->get();
                \Log::info('Total recipients for blog "' . $blog->blog_title . '": ' . $recipients->count());
    
                // Process recipients in batches
                foreach ($recipients->chunk($batchSize) as $batch) {
                    foreach ($batch as $entry) {
                        $email = $entry->$emailField;
                        $firstName = $entry->$nameField;
    
                        $blogIntro = str_replace('[fname]', $firstName, $blog->blog_intro);
    
                        Mail::to($email)->send(new DailyBlogReport($blog, $firstName, $blogIntro));
                        \Log::info('Email sent to: ' . $email . ' for blog: ' . $blog->blog_title);
                    }
    
                    // Delay for 5 minutes (300 seconds) after each batch
                    \Log::info('Batch of ' . $batchSize . ' emails sent. Waiting 5 minutes before the next batch.');
                    sleep(300);
                }
            }
        } else {
            \Log::info('No approved blogs found for today, no emails sent.');
        }
    }
     
}
