<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    // POST /posts/{post}/comments
    // StoreCommentRequest is type-hinted, so authorize() and rules() run before this method.
    // Post $post is route model binding: Laravel finds the post from {post} in the URL (404 if missing).
    public function store(StoreCommentRequest $request, Post $post)
    {
        // creating through $post->comments() fills post_id automatically.
        // ...$request->validated() spreads only the checked fields (body) into the array,
        // and user_id comes from the logged-in user, not from the form.
        $post->comments()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        // with() puts a one-time "flash" message in the session; the layout shows it
        return redirect()->route('posts.show', $post)->with('status', 'Comment posted.');
    }
}
