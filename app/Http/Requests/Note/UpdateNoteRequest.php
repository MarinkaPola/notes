<?php

namespace App\Http\Requests\Note;

use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @property Note $uuid
 */

class UpdateNoteRequest extends FormRequest
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
            'delete_attachments' => 'array',
            'delete_attachments.*' => [
                Rule::exists('attachments', 'id')
                    ->where('note_id', $this->uuid->id)
            ],
        ];
    }
}
