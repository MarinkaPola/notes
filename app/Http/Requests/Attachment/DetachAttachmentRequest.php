<?php

namespace App\Http\Requests\Attachment;

use App\Models\Note;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Note $uuid
 */

class DetachAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                Rule::exists('attachments', 'id')
                    ->where('note_id', $this->uuid->id)
            ],
        ];
    }
}
