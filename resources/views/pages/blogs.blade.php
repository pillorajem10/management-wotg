@extends('layouts.layout')

@section('title', 'Blogs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=1.2') }}">
@endsection

@section('content')
    <div class="blog-container">
        <div class="table-container">
            <table class="blog-table">
                <thead>
                    <tr class="blog-table-header">
                        <th class="blog-table-header-cell">Blog Title</th>
                        <th class="blog-table-header-cell">Creator</th>
                        <th class="blog-table-header-cell">Date Added</th>
                        <th class="blog-table-header-cell">Thumbnail</th>
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
                                @if($blog->blog_thumbnail)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" style="max-width: 100px; height: auto;">
                                @else
                                    No Thumbnail
                                @endif
                            </td>
                            <td class="blog-table-cell">
                                <a href="{{ route('blogs.show', $blog->id) }}" class="btn-view">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="blog-table-row">
                            <td colspan="5" class="blog-table-empty text-center">No blogs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="{{ route('blogs.create') }}" class="btn-create">Create New Blog</a>
        </div>
    </div>
@endsection
