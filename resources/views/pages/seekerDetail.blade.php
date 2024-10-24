@extends('layouts.layout')

@section('title', 'Seeker Details')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seekerDetails.css?v=1.0') }}">
@endsection

@include('components.loading')

@section('content')
    <div class="back-link">
        <a href="/seekers" class="back-button">
            <span class="arrow">&larr;</span> Go Back
        </a>
    </div>
    <div class="seeker-detail">
        <h2>{{ $seeker->seeker_fname }} {{ $seeker->seeker_lname }}</h2>
        <p><strong>Nickname:</strong> {{ $seeker->seeker_nickname }}</p>
        <p><strong>Gender:</strong> {{ $seeker->seeker_gender }}</p>
        <p><strong>Age:</strong> {{ $seeker->seeker_age }}</p>
        <p><strong>Email:</strong> {{ $seeker->seeker_email }}</p>
        <p><strong>Country:</strong> {{ $seeker->seeker_country }}</p>
        <p><strong>City:</strong> {{ $seeker->seeker_city }}</p>
        <p><strong>Catch From:</strong> {{ $seeker->seeker_catch_from }}</p>
        <p><strong>Status:</strong> {{ $seeker->seeker_status }}</p>

        <h3>Assign Missionary:</h3>
        <form action="{{ route('seekers.updateMissionary', $seeker->id) }}" method="POST">
            @csrf
            <select name="missionary_id" class="missionary-dropdown" onchange="this.form.submit()">
                <option value="">Select a Missionary</option>
                @foreach ($missionaries as $missionary)
                    <option value="{{ $missionary->id }}" {{ $missionary->id == $seeker->seeker_missionary ? 'selected' : '' }}>
                        {{ $missionary->user_fname }} {{ $missionary->user_lname }} - {{ $missionary->seekers_count }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
@endsection
