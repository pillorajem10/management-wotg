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
        // Get search and filter inputs from the request
        $search = $request->input('search', ''); // Default to an empty string if no search query is present
        $userMinistry = $request->input('user_ministry', ''); // Get ministry filter if present
        $dGroupLeader = $request->input('user_dgroup_leader', ''); // Get D-Group leader filter if present
    
        // Fetch users with pagination, applying filters
        $users = User::when($search, function($query, $search) {
                    return $query->where('user_fname', 'like', '%' . $search . '%')
                                 ->orWhere('user_lname', 'like', '%' . $search . '%');
                })
                ->when($userMinistry, function($query, $userMinistry) {
                    return $query->where('user_ministry', $userMinistry);
                })
                ->when($dGroupLeader, function($query, $dGroupLeader) {
                    if ($dGroupLeader === 'none') {
                        return $query->whereNull('user_dgroup_leader');
                    }
                    return $query->where('user_dgroup_leader', $dGroupLeader);
                })
                ->paginate(15);
    
        // Fetch all users to populate the D-Group leader dropdown
        $dGroupLeaders = User::whereNotNull('user_dgroup_leader')
                             ->distinct()
                             ->get(['id', 'user_fname', 'user_lname']);
    
        return view('pages.users', compact('users', 'dGroupLeaders'));
    }               
}
