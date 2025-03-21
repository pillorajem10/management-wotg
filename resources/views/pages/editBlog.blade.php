@extends('layouts.layout')

@section('title', 'Edit Blog')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/editBlog.css?v=2.6') }}">
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
@endsection

@include('components.loading')

@section('content')
    <div class="blog-container">
        <div class="back-link">
            <a href="{{ route('blogs.index', ['search' => session('blog_search_term', ''), 'page' => session('blog_current_page', 1)]) }}" class="back-button btn">
                <span class="arrow">&larr;</span> Go Back to Blogs
            </a>
        </div>        

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="blog-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="blog_title" class="form-label">Blog Title</label>
                <input type="text" name="blog_title" id="blog_title" class="form-input" value="{{ old('blog_title', $blog->blog_title) }}" required>
                @error('blog_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="blog_intro" class="form-label">Blog Intro</label>
                <textarea name="blog_intro" id="blog_intro" class="form-textarea" required>{{ old('blog_intro', $blog->blog_intro) }}</textarea>
                @error('blog_intro')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="blog_body" class="form-label">Blog Body</label>
                <textarea name="blog_body" id="blog_body" class="form-textarea" required>{{ old('blog_body', $blog->blog_body) }}</textarea>
                @error('blog_body')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="blog_release_date_and_time" class="form-label">Release Date and Time</label>
                <input type="date" name="blog_release_date_and_time" id="blog_release_date_and_time" class="form-input" 
                       value="{{ old('blog_release_date_and_time', \Carbon\Carbon::parse($blog->blog_release_date_and_time)->format('Y-m-d')) }}" required>
                @error('blog_release_date_and_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="blog_thumbnail" class="form-label">Thumbnail</label>
                <input type="file" name="blog_thumbnail" id="blog_thumbnail" class="form-file" accept="image/*">
                @error('blog_thumbnail')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @if ($blog->blog_thumbnail)
                <div class="form-group">
                    <img id="image_preview" src="{{ asset('uploads/' . $blog->blog_thumbnail) }}" alt="Current Thumbnail" style="max-width: 300px; margin-top: 10px;"></div>
                </div>
            @endif
        
            <button type="submit" class="btn-submit">
                {{ auth()->user()->user_role === 'owner' ? 'Save And Approve' : 'Save' }}
            </button>
        </form>    

        <script src="{{ asset('js/editBlog.js?v=2.6') }}"></script>
    </div>
@endsection
