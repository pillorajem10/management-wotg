@extends('layouts.layout')

@section('title', $blog->blog_title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogDetails.css?v=2.3') }}">
@endsection

@section('content')
    <div class="blog-details-container">
        <div class="blog-meta d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="blog-creator">
                    Created by: {{ $blog->creator ? $blog->creator->user_fname . ' ' . $blog->creator->user_lname : 'Unknown' }}
                </span>,
                <span class="blog-date">
                    Date Added: {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}
                </span>
            </div>
        
            @if(auth()->user()->user_role === 'owner')
                <form action="{{ route('blogs.approve', $blog->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $blog->blog_approved ? 'warning' : 'success' }}" onclick="return confirm('Are you sure you want to {{ $blog->blog_approved ? 'disapprove' : 'approve' }} this blog?')">
                        {{ $blog->blog_approved ? 'Disapprove' : 'Approve' }}
                    </button>
                </form>
            @endif
        </div>     

        <div class="blog-content">
            <div class="blog-thumbnail">
                @if($blog->blog_thumbnail)
                    <img src="data:image/jpeg;base64,{{ base64_encode($blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" class="thumbnail-image">
                @else
                    <p class="no-thumbnail">No Thumbnail Available</p>
                @endif
            </div>

            <div class="blog-body">
                <div class="body-text">{!! $blog->blog_body !!}</div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('blogs.index', ['search' => session('blog_search_term', ''), 'page' => session('blog_current_page', 1)]) }}" class="btn-back">Back to Blogs</a>
        </div>        
    </div>
@endsection
