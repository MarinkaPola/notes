<?php

namespace App\Http\Requests\Attachment;

use Illuminate\Foundation\Http\FormRequest;

class AttachAttachmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return  [
                'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ];
    }
}
