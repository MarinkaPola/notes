<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Relationship;

class ViewNoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'email',
                'exists:users,email',
                new Relationship()
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.exists' => 'The user must be registered',
        ];
    }
}
