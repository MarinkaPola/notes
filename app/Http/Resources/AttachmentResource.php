<?php

namespace App\Http\Resources;
use App\Models\Attachment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NoteResource
 * @package App\Http\Resources
 * @mixin Attachment
 */

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_original_name' => $this->file_original_name,
        ];
    }
}
