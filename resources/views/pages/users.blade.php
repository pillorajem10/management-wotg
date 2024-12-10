@extends('layouts.layout')

@section('title', 'Users')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/usersList.css?v=2.0') }}">
@endsection

@include('components.loading')

@section('content')
    <div class="user-container">
        <!-- Search Form -->
        <div class="search-container">
            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-4">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by First or Last Name" value="{{ session('search', '') }}">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Search</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>   
            
            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-4">
                <div class="form-group">
                    <select name="user_ministry" class="form-control" onchange="this.form.submit()">
                        <option value="" disabled selected>Select a Ministry</option>
                        <option value="Music Ministry" {{ request('user_ministry') == 'Music Ministry' ? 'selected' : '' }}>Music Ministry</option>
                        <option value="Intercessory" {{ request('user_ministry') == 'Intercessory' ? 'selected' : '' }}>Intercessory</option>
                        <option value="Worship Service" {{ request('user_ministry') == 'Worship Service' ? 'selected' : '' }}>Worship Service</option>
                        <option value="Digital Missionary" {{ request('user_ministry') == 'Digital Missionary' ? 'selected' : '' }}>Digital Missionary</option>
                        <option value="Creatives and Communication" {{ request('user_ministry') == 'Creatives and Communication' ? 'selected' : '' }}>Creatives and Communication</option>
                        <option value="Admin" {{ request('user_ministry') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="D-Group Management" {{ request('user_ministry') == 'D-Group Management' ? 'selected' : '' }}>D-Group Management</option>
                        <option value="None" {{ request('user_ministry') == 'None' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </form>

            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-4">
                <div class="form-group">
                    <select name="user_dgroup_leader" class="form-control" onchange="this.form.submit()">
                        <option value="" selected>Select a D-Group Leader</option>
                        <option value="none" {{ request('user_dgroup_leader') == 'none' ? 'selected' : '' }}>No D-Group Leader</option>
                        
                        <!-- Add authenticated user option -->
                        @if (Auth::check())
                            <option value="{{ Auth::id() }}" {{ request('user_dgroup_leader') == Auth::id() ? 'selected' : '' }}>
                                {{ Auth::user()->user_fname }} {{ Auth::user()->user_lname }}
                            </option>
                        @endif
                        
                        @foreach ($dGroupLeaders as $leader)
                            <option value="{{ $leader->id }}" {{ request('user_dgroup_leader') == $leader->id ? 'selected' : '' }}>
                                {{ $leader->user_fname }} {{ $leader->user_lname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            
        </div>

        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr class="user-table-header">
                        <th class="user-table-header-cell">Name</th>
                        <th class="user-table-header-cell">Email</th>
                        <th class="user-table-header-cell">Church</th>
                        <th class="user-table-header-cell">Ministry</th>
                        <th class="user-table-header-cell">D-Group Leader</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="user-table-row">
                            <td class="user-table-cell">{{ $user->user_fname }} {{ $user->user_lname }}</td>
                            <td class="user-table-cell">{{ $user->email }}</td>
                            <td class="user-table-cell">{{ $user->user_church_name }}</td>
                            <td class="user-table-cell">{{ $user->user_ministry }}</td>
                            <td class="user-table-cell">
                                @if ($user->user_dgroup_leader)
                                    <!-- Fetch and display D-Group leader's name if found -->
                                    @php
                                        $dgroupLeader = App\Models\User::find($user->user_dgroup_leader);
                                    @endphp
                                    @if ($dgroupLeader)
                                        {{ $dgroupLeader->user_fname }} {{ $dgroupLeader->user_lname }}
                                    @else
                                        Not a D-Group member
                                    @endif
                                @else
                                    Not a D-Group member
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="user-table-row">
                            <td colspan="4" class="user-table-empty text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>
    </div>
@endsection
