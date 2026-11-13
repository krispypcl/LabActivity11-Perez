<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // order matters: each seeder needs data from the ones before it
        // authors -> posts (need author_id) -> users (link Kagawad to author)
        // -> tags (need posts) -> comments (need posts and users)
        $this->call([
        AuthorSeeder::class,
        PostSeeder::class,
        UserSeeder::class,
        TagSeeder::class,
        CommentSeeder::class,
        ]);
    }
}
