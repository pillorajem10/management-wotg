@extends('layouts.layout')

@section('title', 'Users')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/usersList.css?v=1.8') }}">
@endsection

@include('components.loading')

@section('content')
    <div>
        <table class="user-table">
            <thead>
                <tr class="user-table-header">
                    <th class="user-table-header-cell">First Name</th>
                    <th class="user-table-header-cell">Last Name</th>
                    <th class="user-table-header-cell">Role</th>
                    <th class="user-table-header-cell">Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="user-table-row">
                        <td class="user-table-cell">{{ $user->user_fname }}</td>
                        <td class="user-table-cell">{{ $user->user_lname }}</td>
                        <td class="user-table-cell">{{ $user->user_role }}</td>
                        <td class="user-table-cell">{{ $user->email }}</td>
                    </tr>
                @empty
                    <tr class="user-table-row">
                        <td colspan="4" class="user-table-empty text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection