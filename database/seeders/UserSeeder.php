<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Creates the login accounts. Runs after AuthorSeeder so the Kagawad author already exists.
     */
    public function run(): void
    {
        // resident account: can log in and comment, but has no author profile so can't post
        User::create([
            'name' => 'Lola Perez',
            'email' => 'lola.perez@bulletin.test',
            'password' => Hash::make('bulletin2026'), // stored as a hash, never plain text
        ]);

        // Kagawad account: same email as the Kagawad author from AuthorSeeder
        $kagawadUser = User::create([
            'name' => 'Kagawad Chris Perez',
            'email' => 'kagawad.perez@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        // link the login account to the author profile by filling authors.user_id.
        // After this, $kagawadUser->author (hasOne) returns this author.
        $kagawadAuthor = Author::where('email', 'kagawad.perez@bulletin.test')->firstOrFail();
        $kagawadAuthor->user_id = $kagawadUser->id;
        $kagawadAuthor->save();

        // 8 more residents from UserFactory (their password is "password")
        User::factory()->count(8)->create();
    }
}
