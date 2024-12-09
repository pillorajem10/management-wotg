<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search', ''); // Default to an empty string if no search query is present
        $userMinistry = $request->input('user_ministry', ''); // Get ministry filter if present
        
        // Fetch users with pagination, filter by first name, last name, and ministry if filters are provided
        $users = User::when($search, function($query, $search) {
                return $query->where('user_fname', 'like', '%' . $search . '%')
                             ->orWhere('user_lname', 'like', '%' . $search . '%');
            })
            ->when($userMinistry, function($query, $userMinistry) {
                return $query->where('user_ministry', $userMinistry);
            })
            ->paginate(15);
    
        return view('pages.users', compact('users'));
    }          
}
