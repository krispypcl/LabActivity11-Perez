<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Creates my 5 tags and links them to posts through the post_tag pivot table.
     */
    public function run(): void
    {
        // the 4 required tags + my own tag "Matina Crossing" (the barangay name)
        foreach (['Purok Perez', 'Urgent', 'Libre', 'Schedule', 'Matina Crossing'] as $name) {
            Tag::create(['name' => $name]);
        }

        $tags = Tag::all();

        // give every post 1-3 random tags.
        // attach() adds rows to post_tag through the tags() belongsToMany relationship,
        // so I never insert into the pivot table by hand.
        foreach (Post::all() as $post) {
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }

        // sync() is different from attach(): it REMOVES any tags not in the list,
        // so the pinned post ends up with exactly Purok Perez + Urgent, no matter what it got above.
        $pinned = Post::where('is_pinned', true)->firstOrFail();
        $pinned->tags()->sync(
            Tag::whereIn('name', ['Purok Perez', 'Urgent'])->pluck('id')
        );
    }
}
