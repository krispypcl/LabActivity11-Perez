@extends('layouts.app')

@section('title', 'New post – Barangay Bulletin – Purok Perez')

@section('content')
    <a href="{{ route('posts.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back to posts</a>

    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-xl font-bold mb-4">New post</h1>

        {{-- Error summary: lists every message when StorePostRequest fails validation --}}
        @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 text-red-700 px-4 py-2 text-sm">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- POST /posts -> PostController@store. Each field below uses old() to keep what was typed
             and @error to show its own message under it --}}
        <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1">Title</label>
                <input id="title" name="title" value="{{ old('title') }}"
                       class="w-full rounded border px-3 py-2 @error('title') border-red-500 @else border-gray-300 @enderror">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium mb-1">Category</label>
                <select id="category" name="category"
                        class="w-full rounded border px-3 py-2 @error('category') border-red-500 @else border-gray-300 @enderror">
                    <option value="">Choose a category</option>
                    {{-- @selected re-selects the category that was picked before the error --}}
                    @foreach ($categories as $c)
                        <option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium mb-1">Body</label>
                <textarea id="body" name="body" rows="6"
                          class="w-full rounded border px-3 py-2 @error('body') border-red-500 @else border-gray-300 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="block text-sm font-medium mb-1">Tags</p>
                <div class="flex flex-wrap gap-4">
                    @foreach ($tags as $tag)
                        <label class="flex items-center gap-1 text-sm">
                            {{-- name="tags[]" sends the checked ids as an array.
                                 old('tags', []) is the list checked before the error, so @checked ticks them again --}}
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, old('tags', [])))>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                {{-- tags.* = an error on one of the tag ids (e.g. an id that doesn't exist) --}}
                @error('tags.*')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm">
                    {{-- value 1 when checked; not sent at all when unchecked (controller treats that as false) --}}
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))>
                    Publish now
                </label>
                @error('is_published')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="rounded bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2">Save post</button>
        </form>
    </div>
@endsection
