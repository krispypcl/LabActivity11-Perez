<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Author extends Model
{
    use HasFactory;

    // user_id added in Lab 11 so the seeder can link an author to a login account
    protected $fillable = ['name', 'position', 'email', 'user_id'];

    // belongsTo because the foreign key (user_id) is on the authors table.
    // This is the other side of User hasOne Author.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // one author writes many posts (posts.author_id points back here)
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // hasManyThrough: comments have no author_id, so Laravel goes
    // authors.id -> posts.author_id -> comments.post_id to find them.
    // Used for "N comments received" on the author page.
    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
}
