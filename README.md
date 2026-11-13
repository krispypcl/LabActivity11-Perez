# Barangay Bulletin – Purok Perez

Lab Activity 11: Relationships, eager loading and Form Requests for the Barangay Matina Crossing Hall bulletin (Davao City).
Posts now show the author's name and tags, logged-in residents can comment, and the Kagawad can write posts from the site.

## Seeded logins
Both use the password `bulletin2026` (stored hashed).

| Name | Email | Role |
|---|---|---|
| Lola Perez | `lola.perez@bulletin.test` | Resident (can comment, cannot post) |
| Kagawad Chris Perez | `kagawad.perez@bulletin.test` | Kagawad (linked to the author profile, can post and comment) |

There are also 8 factory residents (password `password`).

## Tags
Purok Perez, Urgent, Libre, Schedule, Matina Crossing (my own tag, the barangay name).
The pinned post always has exactly Purok Perez and Urgent (set with `sync()`).

## Relationships and why
- **User hasOne Author / Author belongsTo User** – only officials have an author profile, and one login account can be linked to at most one author. `authors.user_id` is nullable + unique because residents have no author and an author can exist without a login. Used in `StorePostRequest` (residents get 403) and to create posts with `$user->author->posts()->create()`.
- **Author hasMany Post / Post belongsTo Author** – one author writes many posts, each post has one author (`posts.author_id`).
- **Post hasMany Comment / Comment belongsTo Post** – a post can have many comments, each comment is on one post.
- **User hasMany Comment / Comment belongsTo User** – one user writes many comments, each comment has one writer. `comment->user` shows the commenter's name on the post page, and `$lola->comments()->create()` is used in CommentSeeder.
- **Post belongsToMany Tag (both ways)** – a post has many tags and a tag is on many posts, so it needs the `post_tag` pivot table (composite primary key so the same tag can't be added twice).
- **Author hasManyThrough Comment (via Post)** – comments don't have an `author_id`, but they belong to posts that do, so the author page counts "N comments received" through the posts.

## Eager loading
`Model::preventLazyLoading()` is on outside production, so any N+1 throws an error.
- Index: `with(['author', 'tags'])->withCount('comments')`, plus a `?tag=` filter using `whereHas`.
- Show: `load()` of `author`, `tags`, `comments` (newest first) and `comments.user`.
- Author page: `loadCount('comments')` (hasManyThrough) and posts with `with('tags')->withCount('comments')`.

## Other personalization (from Lab 10)
- **Posts per page:** 6. My last name, "Perez", has only 5 letters, so the minimum of 6 applies.
- **Categories:** Announcement, Health, Peace & Order, Ayuda, Livelihood (my custom one).
- **Pinned post:** "Purok Perez Clean-up Drive", published_at = September 4, 2026 (my birthday), with 3 seeded comments including one from Lola Perez.
- Comment validation message: "Purok Perez comments need at least 3 characters."

## How to run
```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run dev
php artisan serve
```
Then open http://127.0.0.1:8000/posts and log in at /login.
