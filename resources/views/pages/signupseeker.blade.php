@extends('layouts.authLayout')

@section('title', 'Sign Up Seeker')

@section('content')
    <div>
        <h2 class="auth-title">Become part of our community.</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('seekers.signup.submit') }}" method="POST" class="signup-form">
            @csrf
            <div class="form-group">
                <label for="seeker_fname">First Name</label>
                <input type="text" name="seeker_fname" id="seeker_fname" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_lname">Last Name</label>
                <input type="text" name="seeker_lname" id="seeker_lname" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_nickname">Nickname</label>
                <input type="text" name="seeker_nickname" id="seeker_nickname" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_gender">Gender</label>
                <select name="seeker_gender" id="seeker_gender" class="form-input" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="seeker_age">Age</label>
                <input type="number" name="seeker_age" id="seeker_age" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_email">Email</label>
                <input type="email" name="seeker_email" id="seeker_email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_country">Country</label>
                <input type="text" name="seeker_country" id="seeker_country" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_city">City</label>
                <input type="text" name="seeker_city" id="seeker_city" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="seeker_catch_from">Catch From</label>
                <select name="seeker_catch_from" id="seeker_catch_from" class="form-input" required>
                    <option value="YouTube Comment">YouTube Comment</option>
                    <option value="FB Comment">FB Comment</option>
                    <option value="Email Sign Up">Email Sign Up</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="auth-button">Register</button>
            </div>
        </form>
    </div>
@endsection
