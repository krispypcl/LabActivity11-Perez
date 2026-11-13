<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name'];

    // many-to-many: a tag can be on many posts. Laravel finds the pivot table
    // by joining the singular model names alphabetically -> post_tag
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
