@extends('layouts.layout')

@section('title', 'Add Blog')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/addBlog.css?v=2.0') }}">
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
@endsection

@include('components.loading')

@section('content')
    <div class="blog-container">
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

        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" class="blog-form">
            @csrf
            
            <div class="form-group">
                <label for="blog_title" class="form-label">Blog Title</label>
                <input type="text" name="blog_title" id="blog_title" class="form-input" required>
                @error('blog_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="blog_intro" class="form-label">Blog Intro</label>
                <textarea name="blog_intro" id="blog_intro" class="form-textarea" required>{{ old('blog_intro') }}</textarea>
                @error('blog_intro')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="blog_body" class="form-label">Blog Body</label>
                <textarea name="blog_body" id="blog_body" class="form-textarea" required>{{ old('blog_body') }}</textarea>
                @error('blog_body')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="blog_thumbnail" class="form-label">Thumbnail</label>
                <input type="file" name="blog_thumbnail" id="blog_thumbnail" class="form-file" accept="image/*" required>
                @error('blog_thumbnail')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <img id="image_preview" src="" alt="Image Preview" style="display:none; max-width: 300px; margin-top: 10px;">
            </div>
        
            <div class="form-group">
                <label for="blog_release_date_and_time" class="form-label">Release Date</label>
                <input type="date" name="blog_release_date_and_time" id="blog_release_date_and_time" class="form-input" required>
                @error('blog_release_date_and_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>            
        
            <button type="submit" class="btn-submit">Save</button>
        </form>
        
        <script src="{{ asset('js/addBlog.js?v=2.0') }}"></script>
    </div>
@endsection
