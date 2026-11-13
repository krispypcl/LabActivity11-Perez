<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    // my five Lab 10 categories, kept in one place so the create form dropdown
    // and the in: rule in StorePostRequest always use the same list
    public const CATEGORIES = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Livelihood'];

    protected $fillable = ['author_id', 'title', 'category', 'body',
                            'is_published', 'is_pinned', 'published_at', 'views'];

    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    // each post has one author through posts.author_id
    // (the key is written out to show which column it uses)
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    // one post has many comments (comments.post_id)
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // many-to-many with tags through the post_tag pivot table.
    // attach() / sync() on this relationship add or replace the pivot rows.
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
