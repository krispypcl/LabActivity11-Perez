<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    // GET /authors/{author} - Author $author is found from the URL by route model binding
    public function show(Author $author)
    {
        // loadCount runs one COUNT query on the hasManyThrough relation
        // and puts the result in $author->comments_count ("N comments received")
        $author->loadCount('comments');

        // this author's published posts only, newest first.
        // with('tags') and withCount('comments') load everything the cards need up front,
        // so the view doesn't run one extra query per post (N+1).
        $posts = $author->posts()
            ->where('is_published', true)
            ->with('tags')
            ->withCount('comments')
            ->latest('published_at')
            ->get();

        return view('authors.show', compact('author', 'posts'));
    }
}
