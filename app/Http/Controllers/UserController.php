<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Exports\DGroupLeadersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    public function index(Request $request)
    {
        // Get search and filter inputs from the request
        $search = $request->input('search', ''); 
        $userMinistry = $request->input('user_ministry', ''); 
        $dGroupLeader = $request->input('user_dgroup_leader', ''); 
    
        // Fetch users applying filters
        $users = User::when($search, function ($query, $search) {
                return $query->where('user_fname', 'like', '%' . $search . '%')
                             ->orWhere('user_lname', 'like', '%' . $search . '%');
            })
            ->when($userMinistry, function ($query, $userMinistry) {
                return $query->where('user_ministry', $userMinistry);
            })
            ->when($dGroupLeader, function ($query, $dGroupLeader) {
                if ($dGroupLeader === 'none') {
                    return $query->whereNull('user_dgroup_leader');
                }
                return $query->where('user_dgroup_leader', $dGroupLeader);
            })
            ->get();
    
        // All users without any filtering for comparison
        $allUsers = $users;
    
        // Filter only D-Group leaders
        $dGroupLeaders = $users->where('user_already_a_dgroup_leader', 1);
    
        // Separate by gender
        $maleDGroupLeaders = $dGroupLeaders->where('user_gender', 'male');
        $femaleDGroupLeaders = $dGroupLeaders->where('user_gender', 'female');
    
        // Calculate total members for each group
        $totalMaleMembers = 0;
        foreach ($maleDGroupLeaders as $user) {
            $totalMaleMembers += $allUsers->where('user_dgroup_leader', $user->id)->count();
        }
    
        $totalFemaleMembers = 0;
        foreach ($femaleDGroupLeaders as $user) {
            $totalFemaleMembers += $allUsers->where('user_dgroup_leader', $user->id)->count();
        }
    
        $grandTotalMembers = $totalMaleMembers + $totalFemaleMembers;
        $grandTotalDGroupLeaders = $maleDGroupLeaders->count() + $femaleDGroupLeaders->count();
    
        // Fetch D-Group Leaders for the dropdown
        $dGroupLeadersDropdown = User::whereNotNull('user_dgroup_leader')
                                      ->distinct()
                                      ->get(['id', 'user_fname', 'user_lname']);
    
        // Fetch volunteers (users with non-empty ministry fields)
        $volunteers = $users->where('user_ministry', '!=', 'None')
                             ->whereNotNull('user_ministry');
    
        // Calculate the total number of volunteers
        $totalVolunteers = $volunteers->count();
    
        return view('pages.users', compact(
            'users',
            'allUsers',
            'dGroupLeadersDropdown',
            'maleDGroupLeaders',
            'femaleDGroupLeaders',
            'totalMaleMembers',
            'totalFemaleMembers',
            'grandTotalMembers',
            'grandTotalDGroupLeaders',
            'volunteers',
            'totalVolunteers' // Pass this to the view
        ));
    }

    public function export()
    {
        return Excel::download(new DGroupLeadersExport, 'dgroup-leaders.xlsx');
    }
}
