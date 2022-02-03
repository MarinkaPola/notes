<?php

namespace App\Http\Resources;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NoteResource
 * @package App\Http\Resources
 * @property  Note $resource
 */

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge($this->resource->only([
            'id', 'title', 'text', 'visibility', 'uuid',
            ]), [
            'author' => new UserResource($this->resource->user),
            'attachments'=>  AttachmentResource::collection($this->resource->attachments),
        ]);
    }
}
