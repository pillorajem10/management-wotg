<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MemberRequest;
use Illuminate\Support\Str;

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

    public function show($id)
    {
        // Fetch the user by ID
        $user = User::findOrFail($id);

        // Fetch related information (e.g., members for a D-Group Leader)
        $members = User::where('user_dgroup_leader', $user->id)->get();

        // Pass the data to a view
        return view('pages.userDetails', compact('user', 'members'));
    }

    public function export()
    {
        return Excel::download(new DGroupLeadersExport, 'dgroup-leaders.xlsx');
    }

    // UserController.php
    public function edit($id)
    {
        // Find the user by the given ID
        $user = User::findOrFail($id);

        // Check if the user has a D-Group Leader ID set
        $dgroup_leader_email = null;
        if ($user->user_dgroup_leader) {
            $dgroup_leader = User::find($user->user_dgroup_leader); // Assuming user_dgroup_leader stores the leader's user ID
            if ($dgroup_leader) {
                $dgroup_leader_email = $dgroup_leader->email; // Get the leader's email
            }
        }

        return view('pages.editProfile', compact('user', 'dgroup_leader_email'));
    }

    

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'user_fname' => 'required|string|max:255',
            'user_lname' => 'required|string|max:255',
            'user_nickname' => 'required|string|max:255',
            'user_gender' => 'required|string',
            'user_mobile_number' => 'nullable|string|max:15',
            'user_birthday' => 'required|date',
            'user_ministry' => 'required|string',
            'user_already_a_dgroup_leader' => 'required|boolean',
            'user_already_a_dgroup_member' => 'required|boolean',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user_church_name' => 'required|string|max:255',
            'user_country' => 'required|string|max:255',
            'user_city' => 'required|string|max:255',
            'user_meeting_day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'user_meeting_time' => 'required',
        ]);        
    
        // Check if the D-Group Leader email has changed
        $dgroup_leader_email_changed = false;
        $new_dgroup_leader_email = $request->user_dgroup_leader;
        $new_dgroup_leader = User::where('email', $new_dgroup_leader_email)->first();
    
        if ($new_dgroup_leader) {
            $new_dgroup_leader_id = $new_dgroup_leader->id;
        } else {
            $new_dgroup_leader_id = null;
        }
    
        if ($user->user_dgroup_leader != $new_dgroup_leader_id) {
            $dgroup_leader_email_changed = true;
        }
    
        // Update the user's basic data (excluding D-Group Leader)
        $user->update($validated);
    
        // If D-Group Leader email is changed, send approval request to new D-Group leader
        if ($dgroup_leader_email_changed && !empty($new_dgroup_leader_email)) {
            // Find the new D-Group leader by email
            $dgroupLeader = User::where('email', $new_dgroup_leader_email)->first();
    
            if (!$dgroupLeader) {
                return redirect()
                    ->route('profile.edit', ['id' => $user->id]) // Include the user ID here
                    ->with('error', 'This email is not yet registered. Please ask your D-Group leader to register first so they can accept your request.')
                    ->withInput();
            }
    
            // Generate a new approval token (for the approval request)
            $approvalToken = Str::random(60);
    
            // Save the approval token to the user's record
            $user->approval_token = $approvalToken;
            $user->save();
    
            // Send the approval email to the new D-Group leader
            \Mail::to($dgroupLeader->email)->send(new \App\Mail\DgroupMemberApprovalRequest($dgroupLeader, $request->email, $approvalToken, $dgroupLeader->id));
    
            // Save the MemberRequest record for the D-Group leader change request
            MemberRequest::create([
                'user_id' => $user->id,
                'dgroup_leader_id' => $dgroupLeader->id,
                'status' => 'pending', // Initially set the status to 'pending'
            ]);
    
            // Return with a success message (indicating that the approval request is sent)
            return redirect()
                ->route('profile.edit', ['id' => $user->id]) // Include the user ID here
                ->with('success', 'Your request to change the D-Group leader has been sent for approval!');
        }
    
        // Redirect with success message for other profile updates
        return redirect()
            ->route('profile.edit', ['id' => $user->id]) // Include the user ID here
            ->with('success', 'Profile updated successfully!');
    }
}
