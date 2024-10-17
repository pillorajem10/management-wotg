@extends('layouts.layout')

@section('title', 'Blogs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=1.0') }}">
@endsection

@section('content')
    <div class="blog-container">
        <div class="table-container">
            <table class="blog-table">
                <thead>
                    <tr class="blog-table-header">
                        <th class="blog-table-header-cell">Blog Title</th>
                        <th class="blog-table-header-cell">Creator ID</th>
                        <th class="blog-table-header-cell">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr class="blog-table-row">
                            <td class="blog-table-cell">{{ $blog->blog_title }}</td>
                            <td class="blog-table-cell">{{ $blog->blog_creator }}</td>
                            <td class="blog-table-cell">{{ $blog->created_at }}</td>
                        </tr>
                    @empty
                        <tr class="blog-table-row">
                            <td colspan="3" class="blog-table-empty text-center">No blogs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
