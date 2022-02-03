<?php

namespace App\Http\Controllers\ApiControllers\Notes;

use App\Http\Controllers\Controller;

use App\Http\Requests\Attachment\AttachAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use App\Models\Note;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;


class AttachmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Note $note
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Note $note,Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', $note);

        return AttachmentResource::collection($note->attachments()->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AttachAttachmentRequest $request
     * @param Note $note
     * @return AttachmentResource
     * @throws AuthorizationException
     */
    public function store(AttachAttachmentRequest $request, Note $note)
    {
        $this->authorize('show', $note);

        return new AttachmentResource($note->attachments()->create([
            'file_name' => Attachment::saveFile($request->file('file')),
            'file_original_name' => $request->file('file')->getClientOriginalName(),
            'ext' =>  $request->file('file')->getClientOriginalExtension(),
        ]));

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Note $note
     * @param Attachment $attachment
     * @return AttachmentResource
     * @throws AuthorizationException
     */
    public function show(Request $request,Note $note, Attachment $attachment)
    {
        $this->authorize('show', $note);
        $this->authorize('show', [$note, $attachment]);

        return new AttachmentResource($attachment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AttachAttachmentRequest $request
     * @param Note $note
     * @param Attachment $attachment
     * @return AttachmentResource
     * @throws AuthorizationException
     */
    public function update(AttachAttachmentRequest $request, Note $note, Attachment $attachment)
    {
        $this->authorize('update', $note);
        $this->authorize('update', [$note, $attachment]);

        return new AttachmentResource($note->attachments()->update([
            'file_name' => Attachment::saveFile($request->file('file')),
            'file_original_name' => $request->file('file')->getClientOriginalName(),
            'ext' =>  $request->file('file')->getClientOriginalExtension(),
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Note $note
     * @param Attachment $attachment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Note $note, Attachment $attachment)
    {
        $this->authorize('destroy', [$note]);
        $this->authorize('destroy', [$note, $attachment]);

        $note->attachments()->where('id', $attachment->id)->delete();

        $path = 'attachments/' . $attachment->file_name;
        Storage::delete($path);

        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     *
     * @param Request $request
     * @param Note $note
     * @param Attachment $attachment
     * @return string
     * @throws AuthorizationException
     */
    public function download(Request $request, Note $note, Attachment $attachment): string
    {
        $this->authorize('download', $note);
        $this->authorize('download', [$note, $attachment]);

        return  response()->download($attachment->filePath(), $attachment->file_original_name);
    }

}
