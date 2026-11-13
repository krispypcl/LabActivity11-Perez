<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

// Laravel runs this class automatically before CommentController@store.
// 1) authorize() false -> 403   2) rules() fail -> redirect back with $errors and old input
// Only if both pass does the controller method run.
class StoreCommentRequest extends FormRequest
{
    /**
     * Only logged-in users may comment.
     */
    public function authorize(): bool
    {
        // user() is null for guests
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    // custom message, used instead of Laravel's default "must be at least 3 characters"
    // key format is field.rule
    public function messages(): array
    {
        return [
            'body.min' => 'Purok Perez comments need at least 3 characters.',
        ];
    }
}
