@extends('layouts.layout')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=2.1') }}">
@endsection

<title>@yield('title') || Word On The Go</title>

@section('content')
    <h2>Welcome, {{ $user->user_fname }}!</h2>
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

    <h2>A Sprinkle of Biblical Wisdom</h2>
    <div class="verse-card">
        @if($verseOfTheDay)
            <blockquote>
                <p>{{ $verseOfTheDay->text }}</p>
                <footer>— {{ $verseOfTheDay->reference }}</footer>
            </blockquote>
        @else
            <p>No verse available today.</p>
        @endif
    </div>

@endsection
