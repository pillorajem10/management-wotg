@extends('layouts.layout')

@section('title', 'User Details')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/userDetails.css?v=2.6') }}">
@endsection

@section('content')
    <div class="user-details-container">
        <h2>{{ ucwords(strtolower($user->user_fname)) }} {{ ucwords(strtolower($user->user_lname)) }}</h2>
        
        <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
        <p><strong>Phone #:</strong> {{ $user->user_mobile_number ?? 'N/A' }}</p>

        <p><strong>Total Members:</strong> {{ $members->count() }}</p>

        @if ($members->isNotEmpty())
            <h3>Members</h3>
            <div class="members-table-container">
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th> <!-- New Column for Actions -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $index => $member)
                            <tr>
                                <td>{{ ucwords(strtolower($member->user_fname)) }} {{ ucwords(strtolower($member->user_lname)) }}</td>
                                <td>
                                    <a href="{{ route('profile.edit', $member->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No members found.</p>
        @endif

        <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">Back to List</a>
    </div>
@endsection
