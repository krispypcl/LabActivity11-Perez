<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts - list with search, category and tag filters
    public function index(Request $request)
    {
        $posts = Post::query()
            // eager load: 1 query for all authors + 1 for all tags,
            // instead of 1 query per post when the view uses $post->author / $post->tags
            ->with(['author', 'tags'])
            // adds a comments_count column using a subquery (doesn't load the comments themselves)
            ->withCount('comments')
            ->where('is_published', true) // drafts are never listed
            // when() only adds the condition if that filter is in the URL
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            // whereHas: keep only posts that have at least one tag with this name
            ->when($request->filled('tag'), fn ($query) => $query->whereHas('tags', fn ($t) => $t->where('name', $request->tag)))
            ->orderByDesc('is_pinned') // pinned post first
            ->latest('published_at')
            ->paginate(6) // "Perez" has 5 letters, so the minimum of 6 applies
            ->withQueryString(); // keeps ?q=, ?category= and ?tag= on page links

        // list for the category dropdown
        $categories = Post::query()->where('is_published', true)->distinct()->orderBy('category')->pluck('category');

        return view('posts.index', compact('posts', 'categories'));
    }

    // GET /posts/create - form for authors (route has auth middleware)
    public function create()
    {
        $tags = Tag::orderBy('name')->get(); // for the tag checkboxes
        $categories = Post::CATEGORIES;     // for the category dropdown

        return view('posts.create', compact('tags', 'categories'));
    }

    // POST /posts - StorePostRequest already checked the user is an author and validated the input
    public function store(StorePostRequest $request)
    {
        // validated data without tags, because tags aren't a column on posts
        // (they go to the post_tag pivot table below)
        $data = $request->safe()->except('tags');

        // unchecked checkbox isn't sent at all, so turn it into true/false
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        // create through the logged-in user's author -> posts relationship,
        // so author_id is filled automatically (User hasOne Author, Author hasMany Post)
        $post = $request->user()->author->posts()->create($data);

        // save the checked tags into post_tag
        $post->tags()->sync($request->validated('tags'));

        if ($post->is_published) {
            return redirect()->route('posts.show', $post)->with('status', 'Post published.');
        }

        // drafts can't be opened on the show page, so go back to the list
        return redirect()->route('posts.index')->with('status', 'Post saved as draft.');
    }

    // GET /posts/{id}
    public function show($id)
    {
        // findOrFail gives a 404 for drafts or ids that don't exist
        $post = Post::where('is_published', true)->findOrFail($id);

        $post->views += 1;
        $post->save();

        // load() = eager loading on a model we already have.
        // 'comments' gets a closure so they come back newest first,
        // and 'comments.user' (nested) loads every commenter in one query.
        $post->load([
            'author',
            'tags',
            'comments' => fn ($query) => $query->latest(),
            'comments.user',
        ]);

        return view('posts.show', compact('post'));
    }
}
