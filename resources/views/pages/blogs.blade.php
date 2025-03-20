@extends('layouts.layout')

@section('title', 'Blogs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=2.6') }}">
@endsection

@include('components.loading')

@section('content')
    <div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="search-container">
            <form action="{{ route('blogs.index') }}" method="GET" class="search-form">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Blog Title" class="search-input">
                <button type="submit" class="search-button">Search</button>
                <a href="{{ route('blogs.index') }}" class="search-clear">Clear</a>
            </form>
        </div>
        
        <div class="table-container">
            <table class="blog-table">
                <thead>
                    <tr class="blog-table-header">
                        <th class="blog-table-header-cell">Blog Title</th>
                        <th class="blog-table-header-cell">Creator</th>
                        <th class="blog-table-header-cell">Date Added</th>
                        <th class="blog-table-header-cell">Release Date</th>
                        <th class="blog-table-header-cell">Thumbnail</th>
                        <th class="blog-table-header-cell">Approved</th> 
                        <th class="blog-table-header-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr class="blog-table-row">
                            <td class="blog-table-cell">{{ $blog->blog_title }}</td>
                            <td class="blog-table-cell">{{ $blog->creator ? $blog->creator->user_fname . ' ' . $blog->creator->user_lname : 'Unknown' }}</td>
                            <td class="blog-table-cell">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</td>
                            <td class="blog-table-cell">
                                {{ $blog->blog_release_date_and_time ? \Carbon\Carbon::parse($blog->blog_release_date_and_time)->format('F j, Y') : 'Not Set' }}
                            </td>
                            <td class="blog-table-cell">
                                @if($blog->blog_thumbnail)
                                    <img src="{{ asset('uploads/' . $blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" style="max-width: 100px; height: auto;" loading="lazy" onload="this.style.filter='none';">
                                @else
                                    No Thumbnail
                                @endif
                            </td>                            
                            <td class="blog-table-cell">
                                @if($blog->blog_approved)
                                    <span class="text-success">&#10004;</span>
                                @else
                                    <span class="text-danger">&#10008;</span>
                                @endif
                            </td>
                            <td class="blog-table-cell">
                                <div class="action-container">
                                    <a href="{{ route('blogs.show', $blog->id) }}" class="btn-view">View</a>
                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn-view">Edit</a>

                                    @if(auth()->user()->user_role === 'owner')
                                        <form action="{{ route('blogs.approve', $blog->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $blog->blog_approved ? 'warning' : 'success' }} ml-2" onclick="return confirm('Are you sure you want to {{ $blog->blog_approved ? 'disapprove' : 'approve' }} this blog?')">
                                                {{ $blog->blog_approved ? 'Disapprove' : 'Approve' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ml-2" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button>
                                    </form>
                                </div>
                            </td>                            
                        </tr>
                    @empty
                        <tr class="blog-table-row">
                            <td colspan="7" class="blog-table-empty text-center">No blogs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="{{ route('blogs.create') }}" class="btn-create">Create New Blog</a>
        </div>

        <div class="pagination-container">
            {{ $blogs->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
