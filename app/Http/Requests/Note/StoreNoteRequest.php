<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:20',
            'text' => 'required|string|max:200',
            'visibility' => 'required|boolean',
            'file' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
