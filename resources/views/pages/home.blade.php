@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
    <h2>Welcome, {{ $user->user_fname }}!</h2>
    <p>This is the home page of your dashboard panel.</p>
@endsection
