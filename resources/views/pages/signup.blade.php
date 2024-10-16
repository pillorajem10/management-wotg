@extends('layouts.authLayout')

@section('title', 'Sign Up')

@section('content')
    <div>
        <h2 class="auth-title">Sign Up</h2>

        {{-- 
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_fname">First Name</label>
                <input type="text" name="user_fname" id="user_fname" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="user_lname">Last Name</label>
                <input type="text" name="user_lname" id="user_lname" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
            </div>

            <div class="form-group">
                <button type="submit" class="auth-button">Create Account</button>
            </div>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <p>Already got an account? <a href="{{ route('auth.login') }}">Login here</a>.</p>
        </div>
    </div>
@endsection
