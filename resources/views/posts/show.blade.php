{{-- Use the shared layout --}}
@extends('layouts.app')

{{-- Browser tab title includes the post title --}}
@section('title', $post->title . ' – Barangay Bulletin – Purok Perez')

@section('content')
    {{-- Link back to the list --}}
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back to posts</a>

    {{-- $post comes from PostController@show (author, tags and comments.user are already loaded) --}}
    <article class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            {{-- "Pinned" badge only for pinned posts --}}
            @if ($post->is_pinned)
                <span class="text-xs font-semibold bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Pinned</span>
            @endif
            {{-- Category badge --}}
            <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">{{ $post->category }}</span>
            {{-- Tag badges --}}
            @foreach ($post->tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->name]) }}"
                   class="text-xs bg-sky-100 text-sky-800 hover:bg-sky-200 px-2 py-0.5 rounded">#{{ $tag->name }}</a>
            @endforeach
        </div>
        <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
        {{-- Author link, date and view count (already incremented and saved by the controller) --}}
        <p class="text-sm text-gray-500 mt-1">
            By <a href="{{ route('authors.show', $post->author) }}" class="text-emerald-600 hover:underline">{{ $post->author->name }}</a>
            · {{ $post->published_at?->format('F d, Y') }} · {{ $post->views }} views
        </p>
        {{-- {{ }} escapes the body so HTML in it can't run; whitespace-pre-line keeps line breaks --}}
        <div class="mt-4 whitespace-pre-line">{{ $post->body }}</div>
    </article>

    {{-- Comments section --}}
    <section class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Comments ({{ $post->comments->count() }})</h2>

        {{-- Comment form only for logged-in users --}}
        @auth
            {{-- Error summary: $errors is filled when StoreCommentRequest fails validation --}}
            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 text-red-700 px-4 py-2 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- POST /posts/{post}/comments -> CommentController@store --}}
            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                @csrf
                <label for="body" class="block text-sm font-medium mb-1">Write a comment</label>
                {{-- @error turns the border red; old('body') keeps the typed text after an error --}}
                <textarea id="body" name="body" rows="3"
                          class="w-full rounded border px-3 py-2 @error('body') border-red-500 @else border-gray-300 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <button class="mt-2 rounded bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2">Post comment</button>
            </form>
        @else
            {{-- guests (@auth is false) see a link instead of the form --}}
            <p class="mb-6 text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Sign in to comment</a>
            </p>
        @endauth

        {{-- Newest first (ordered in the controller's load()) --}}
        <div class="space-y-4">
            @forelse ($post->comments as $comment)
                <div class="border-t border-gray-100 pt-3">
                    <p class="text-sm">
                        {{-- commenter's name: comments.user was eager loaded, so no extra query here --}}
                        <span class="font-semibold">{{ $comment->user->name }}</span>
                        <span class="text-gray-500">· {{ $comment->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="mt-1 whitespace-pre-line">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">No comments yet.</p>
            @endforelse
        </div>
    </section>
@endsection
