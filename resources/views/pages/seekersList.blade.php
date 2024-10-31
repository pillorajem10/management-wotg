@extends('layouts.layout')

@section('title', 'Seekers List')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seekersList.css?v=1.9') }}">
@endsection

@include('components.loading')

@section('content')
    <div class="seeker-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search Form -->
        <div class="search-container">
            <form method="GET" action="{{ route('seekers.index') }}" class="search-form">
                <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Search by Name" class="search-input" />
                <button type="submit" class="search-button">Search</button>
            </form>
            <a href="{{ route('seekers.index') }}" class="clear-button">Clear</a>
        </div>

        <div class="table-responsive">
            <table class="seeker-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Assigned Missionary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seekers as $seeker)
                    <tr>
                        <td>{{ $seeker->seeker_fname }} {{ $seeker->seeker_lname }}</td>
                        <td>{{ $seeker->missionary ? $seeker->missionary->user_fname . ' ' . $seeker->missionary->user_lname : 'No Assigned Missionary' }}</td>
                        <td>{{ $seeker->seeker_status }}</td>
                        <td>
                            <a href="{{ route('seekers.view', $seeker->id) }}" class="view-button">View</a>
                        </td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>            
        </div>

        <!-- Pagination Links -->
        <div class="pagination-wrapper">
            {{ $seekers->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
