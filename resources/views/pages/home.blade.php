@extends('layouts.layout')

@section('title', 'Home')

@section('content')
    <h2>Welcome, {{ $user->user_fname }}!</h2>
    <p>This is the home page of your admin panel.</p>
@endsection
