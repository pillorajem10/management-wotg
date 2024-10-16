<?php

namespace App\Http\Controllers;

use App\Models\Seeker;
use App\Mail\SeekerEmail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SeekerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function index()
    {
        // Check if the user is not logged in
        if (!auth()->check()) {
            return redirect()->route('auth.login'); // Redirect to the login page
        }
    
        // Retrieve all seekers from the database
        $seekers = Seeker::all();
    
        // Return the pages/seekerList view and pass the seekers data
        return view('pages.seekersList', compact('seekers'));
    }

    // Show the sign-up form for seekers
    public function showSignupForm()
    {
        return view('pages.signupseeker');
    }

    // Handle the sign-up for seekers
    public function signup(Request $request)
    {
        // Validate the request
        $request->validate([
            'seeker_fname' => 'required|string|max:255',
            'seeker_lname' => 'required|string|max:255',
            'seeker_nickname' => 'nullable|string|max:255',
            'seeker_gender' => 'required|string',
            'seeker_age' => 'required|integer',
            'seeker_email' => 'required|string|email|max:255|unique:seekers',
            'seeker_country' => 'required|string|max:255',
            'seeker_city' => 'required|string|max:255',
            'seeker_catch_from' => 'nullable|string|max:255',
        ]);
    
        // Create a new seeker with default values
        Seeker::create(array_merge($request->all(), [
            'seeker_missionary' => null,          // Default to null
            'seeker_dgroup_leader' => null,       // Default to null
            'seeker_status' => 'Infant',           // Default to 'Infant'
        ]));
    
        // Redirect or return response
        return redirect()->route('seekers.signup')->with('success', 'Thank you for registering and decided to become part of our community.');
    }
    
    // Show the details of a specific seeker
    public function show($id)
    {
        // Retrieve the seeker by ID
        $seeker = Seeker::findOrFail($id);

        // Retrieve users with the role 'missionary'
        $missionaries = User::where('user_role', 'missionary')->get();

        // Return the seeker detail view and pass the seeker and missionaries data
        return view('pages.seekerDetail', compact('seeker', 'missionaries'));
    }

    public function updateMissionary(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'missionary_id' => 'required|exists:users,id',
        ]);

        // Retrieve the seeker by ID
        $seeker = Seeker::findOrFail($id);
        
        // Update the seeker's assigned missionary
        $seeker->seeker_missionary = $request->missionary_id;
        $seeker->save();

        // Redirect back with a success message
        return redirect()->route('seekers.view', $id)->with('success', 'Missionary updated successfully!');
    }


    // Send email to selected seekers
    public function sendSeekerEmail(Request $request)
    {
        \Log::info('Request data:', $request->all()); // Log all incoming request data
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'emails' => 'required|string', // Validate the emails field
        ]);
    
        // Split the emails into an array
        $emails = explode(',', $request->emails);
    
        // Log the email addresses
        \Log::info('Sending emails to the following addresses:', $emails);
    
        // Send an email to each address
        foreach ($emails as $email) {
            Mail::to(trim($email))->send(new SeekerEmail($email, $request->subject, $request->body));
        }
    
        return redirect()->back()->with('success', 'Emails sent successfully!');
    }    
}
