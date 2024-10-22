@extends('layouts.layout')

@section('title', $blog->blog_title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogDetails.css?v=1.0') }}">
@endsection

@section('content')
    <div class="blog-details-container">
        <div class="blog-meta">
            <span class="blog-creator">
                Created by: {{ $blog->creator ? $blog->creator->user_fname . ' ' . $blog->creator->user_lname : 'Unknown' }}
            </span>,
            <span class="blog-date">
                Date Added: {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}
            </span>
        </div>

        <div class="blog-content">
            <div class="blog-flex">
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
        </div>

        <div class="text-center">
            <a href="{{ route('blogs.index') }}" class="btn-back">Back to Blogs</a>
        </div>
    </div>
@endsection
