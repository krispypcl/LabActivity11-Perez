<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Runs last in DatabaseSeeder because comments need posts AND users to exist first.
     */
    public function run(): void
    {
        // all 10 users (Lola, the Kagawad and the 8 factory residents) can be commenters
        $users = User::all();

        // only published posts get comments; the pinned post is skipped here
        // because it gets its own fixed comments below
        $posts = Post::where('is_published', true)->where('is_pinned', false)->get();

        foreach ($posts as $post) {
            // rand(0, 5) = some posts get no comments, some get up to 5
            // sequence() runs once per comment, so each one gets a different random user
            Comment::factory()->count(rand(0, 5))
                ->sequence(fn ($seq) => ['user_id' => $users->random()->id])
                ->create(['post_id' => $post->id]);
        }

        // --- my pinned birthday post: 3 comments, one of them by Lola Perez ---
        $pinned = Post::where('is_pinned', true)->firstOrFail();
        $lola = User::where('email', 'lola.perez@bulletin.test')->firstOrFail();
        $others = $users->where('id', '!=', $lola->id); // everyone except Lola

        // for($pinned) fills post_id using Comment's post() belongsTo relationship.
        // created_at is set to the past so the "newest first" order on the show page is easy to see
        // (the factory is used here because created_at is not in Comment's $fillable).
        Comment::factory()->for($pinned)->create([
            'user_id' => $others->random()->id,
            'body' => 'Asa mi mag-meet? Sa may hall ba mismo?',
            'created_at' => now()->subDays(2),
        ]);

        Comment::factory()->for($pinned)->create([
            'user_id' => $others->random()->id,
            'body' => 'Count me in, 6 AM sharp.',
            'created_at' => now()->subDay(),
        ]);

        // Lola's comment is created through User hasMany Comment, so user_id is filled in automatically.
        // It is the newest one, so it shows first on the pinned post.
        $lola->comments()->create([
            'post_id' => $pinned->id,
            'body' => 'Salamat Kagawad! Magdala ko ug sako ug walis para sa Purok Perez.',
        ]);
    }
}
