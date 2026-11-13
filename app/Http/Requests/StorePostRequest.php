<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Runs before PostController@store, so the controller doesn't need $request->validate()
class StorePostRequest extends FormRequest
{
    /**
     * Only users who have an author profile (User hasOne Author) can post.
     * Residents have no author, so they get a 403.
     */
    public function authorize(): bool
    {
        // ?-> so a guest (null user) also returns false instead of crashing
        return $this->user()?->author !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:20'],
            // Rule::in = the "in:" rule; only my 5 categories are accepted
            'category' => ['required', Rule::in(Post::CATEGORIES)],
            // tags come from checkboxes named tags[], so they arrive as an array
            'tags' => ['required', 'array', 'min:1'],
            // tags.* checks every item in the array: each id must exist in the tags table
            'tags.*' => ['exists:tags,id'],
            // checkbox: "1" when checked, missing when unchecked
            'is_published' => ['boolean'],
        ];
    }
}
