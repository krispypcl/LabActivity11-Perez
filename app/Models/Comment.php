<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    // columns that create() is allowed to fill (mass assignment)
    protected $fillable = ['post_id', 'user_id', 'body'];

    // the post this comment is on (comments.post_id)
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // who wrote the comment (comments.user_id); used to show the commenter's name
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
