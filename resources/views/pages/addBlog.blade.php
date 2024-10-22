@extends('layouts.layout')

@section('title', 'Add Blog')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/addBlog.css?v=1.1') }}">
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script> <!-- CKEditor script -->
@endsection

@section('content')
    <div class="blog-container">
        <h2 class="page-title">Add New Blog</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
                <label for="blog_body" class="form-label">Blog Body</label>
                <textarea name="blog_body" id="blog_body" class="form-textarea" required></textarea>
                @error('blog_body')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="blog_thumbnail" class="form-label">Thumbnail (optional)</label>
                <input type="file" name="blog_thumbnail" id="blog_thumbnail" class="form-file" accept="image/*">
                @error('blog_thumbnail')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Submit</button>
        </form>
    </div>

    <script>
        // Initialize CKEditor
        CKEDITOR.replace('blog_body', {
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', 'SelectAll'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                { name: 'styles', items: ['Styles', 'Format'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
            ],
        });
    </script>
@endsection
