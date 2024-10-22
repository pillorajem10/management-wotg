@extends('layouts.layout')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=1.4') }}">
@endsection

@section('content')
    <h2>Welcome, {{ $user->user_fname }}!</h2>
    <p>This is the home page of your dashboard panel.</p>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <h3><a href="{{ route('blogs.index') }}">Blogs Count</a></h3>
            <p><a href="{{ route('blogs.index') }}">{{ $blogCount }}</a></p>
        </div>
        <div class="dashboard-card">
            <h3><a href="{{ route('users.index') }}">Missionaries Count</a></h3>
            <p><a href="{{ route('users.index') }}">{{ $userCount }}</a></p>
        </div>
        <div class="dashboard-card">
            <h3><a href="{{ route('seekers.index') }}">Seekers Count</a></h3>
            <p><a href="{{ route('seekers.index') }}">{{ $seekerCount }}</a></p>
        </div>
    </div>
@endsection
