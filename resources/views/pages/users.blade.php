@extends('layouts.layout')

@section('title', 'Users')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/usersList.css?v=2.3') }}">
@endsection

@include('components.loading')

@section('content')
    <div class="user-container">
        <!-- Male D-Group Leaders -->
        <div class="table-container">
            <h3>Men</h3>
            <table class="user-table">
                <thead>
                    <tr class="user-table-header">
                        <th class="user-table-header-cell">Name</th>
                        <th class="user-table-header-cell">Members</th>
                        <th class="user-table-header-cell">Day & Time</th>
                        <th class="user-table-header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($maleDGroupLeaders as $user)
                        <tr class="user-table-row">
                            <td class="user-table-cell">{{ ucwords(strtolower($user->user_fname)) }} {{ ucwords(strtolower($user->user_lname)) }}</td>
                            <td class="user-table-cell">
                                {{ $allUsers->where('user_dgroup_leader', $user->id)->count() }}
                            </td>
                            <td class="user-table-cell">
                                {{ $user->user_meeting_day ?? null }} 
                                {{
                                    $user->user_meeting_time
                                        ? \Carbon\Carbon::createFromFormat('H:i:s', $user->user_meeting_time)->format('g:i A')
                                        : null
                                }}
                            </td>
                            <td class="user-table-cell">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-view-leader">View</a>
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-view-leader">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="user-table-row">
                            <td colspan="4" class="user-table-empty text-center">No male D-Group Leaders found.</td>
                        </tr>
                    @endforelse
                    <tr class="user-table-row total-row">
                        <td class="user-table-cell"><strong>Total For Men</strong></td>
                        <td class="user-table-cell"></td>
                        <td class="user-table-cell">
                            <strong>Total D-Group Leaders: {{ $maleDGroupLeaders->count() }}</strong><br>
                            <strong>Total Members: {{ $totalMaleMembers }}</strong>
                        </td>
                        <td class="user-table-cell"></td> <!-- Empty cell for the "Action" column -->
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Female D-Group Leaders Section -->
        <div class="table-container">
            <h3>Women</h3>
            <table class="user-table">
                <thead>
                    <tr class="user-table-header">
                        <th class="user-table-header-cell">Name</th>
                        <th class="user-table-header-cell">Members</th>
                        <th class="user-table-header-cell">Day & Time</th>
                        <th class="user-table-header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($femaleDGroupLeaders as $user)
                        <tr class="user-table-row">
                            <td class="user-table-cell">{{ ucwords(strtolower($user->user_fname)) }} {{ ucwords(strtolower($user->user_lname)) }}</td>
                            <td class="user-table-cell">
                                {{ $allUsers->where('user_dgroup_leader', $user->id)->count() }}
                            </td>
                            <td class="user-table-cell">
                                {{ $user->user_meeting_day ?? null }} 
                                {{
                                    $user->user_meeting_time
                                        ? \Carbon\Carbon::createFromFormat('H:i:s', $user->user_meeting_time)->format('g:i A')
                                        : null
                                }}
                            </td>
                            <td class="user-table-cell">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-view-leader">View</a>
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-view-leader">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="user-table-row">
                            <td colspan="4" class="user-table-empty text-center">No female D-Group Leaders found.</td>
                        </tr>
                    @endforelse
                    <tr class="user-table-row total-row">
                        <td class="user-table-cell"><strong>Total For Women</strong></td>
                        <td class="user-table-cell"></td>
                        <td class="user-table-cell">
                            <strong>Total D-Group Leaders: {{ $femaleDGroupLeaders->count() }}</strong><br>
                            <strong>Total Members: {{ $totalFemaleMembers }}</strong>
                        </td>
                        <td class="user-table-cell"></td> <!-- Empty cell for the "Action" column -->
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="grand-total-container">
            <div class="grand-total-summary">
                <p><strong>Total Leaders: {{ $grandTotalDGroupLeaders }}</strong></p>
                <p><strong>Total Members: {{ $grandTotalMembers }}</strong></p>
            </div>
        </div>

        <div class="table-container">
            <h3>Volunteers</h3>
            <table class="user-table">
                <thead>
                    <tr class="user-table-header">
                        <th class="user-table-header-cell">Name</th>
                        <th class="user-table-header-cell">Ministry</th>
                        <th class="user-table-header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($volunteers as $user)
                        <tr class="user-table-row">
                            <td class="user-table-cell">{{ ucwords(strtolower($user->user_fname)) }} {{ ucwords(strtolower($user->user_lname)) }}</td>
                            <td class="user-table-cell">{{ $user->user_ministry }}</td>
                            <td class="user-table-cell">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-view-leader">View</a>
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-view-leader">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="user-table-row">
                            <td colspan="2" class="user-table-empty text-center">No volunteers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="grand-total-container">
            <div class="grand-total-summary">
                <p><strong>Total Volunteers: {{ $totalVolunteers }}</strong></p>
            </div>
        </div>  
        
        <a href="{{ route('users.dgroup') }}" class="btn btn-success">Export as Excel</a>
    </div>
@endsection
