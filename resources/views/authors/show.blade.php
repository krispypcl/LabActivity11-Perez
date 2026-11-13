@extends('layouts.app')

@section('title', $author->name . ' – Barangay Bulletin – Purok Perez')

@section('content')
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back to posts</a>

    {{-- Author profile: $author and $posts come from AuthorController@show --}}
    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
        <p class="text-gray-600">{{ $author->position }}</p>
        {{-- comments_count comes from loadCount('comments') = hasManyThrough via posts --}}
        <p class="mt-2 text-sm text-gray-500">
            {{ $posts->count() }} published posts · {{ $author->comments_count }} comments received
        </p>
    </div>

    <h2 class="text-lg font-semibold mt-6 mb-3">Posts</h2>

    {{-- Only published posts; tags and comments_count were loaded in the controller (no N+1) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($posts as $post)
            <article class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">{{ $post->category }}</span>
                <h3 class="text-lg font-semibold mt-2">
                    <a href="{{ route('posts.show', $post->id) }}" class="hover:text-emerald-600">{{ $post->title }}</a>
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $post->published_at?->format('M d, Y') }} · {{ $post->comments_count }} comments
                </p>
                {{-- Tag badges link back to the list filtered with ?tag= --}}
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                           class="text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 px-2 py-0.5 rounded">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </article>
        @empty
            <p class="md:col-span-2 text-gray-600">No published posts yet.</p>
        @endforelse
    </div>
@endsection
