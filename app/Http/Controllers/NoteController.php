<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attachment\DetachAttachmentRequest;
use App\Http\Requests\Attachment\DownloadAttachmentRequest;
use App\Http\Requests\Note\ViewNoteRequest;
use App\Models\Attachment;
use App\Models\Note;
use App\Models\User;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;


class NoteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        return View('dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return View('notes.note_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(StoreNoteRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Note $note */
        $note = $user->notes()->create(array_merge($request->only(
            'title', 'text', 'visibility'
        ), [
            'uuid' => Str::uuid()
            ]));

        if ($request->has('file')) {
            $note->attachments()->create([
                'file_name' => Attachment::saveFile($request->file('file')),
                'file_original_name' => $request->file('file')->getClientOriginalName(),
                'ext' =>  $request->file('file')->getClientOriginalExtension()
            ]);
        }

        return redirect(route('notes.show', $note->uuid));
    }

    /**
     * Display the specified resource.
     *
     * @param Note $note
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function show(Note $note)
    {
        $this->authorize('show', $note);

        return View('notes.note')->with('note', $note->load('user', 'attachments'));
    }

    /**
     * Display the specified resource.
     *
     * @param Note $note
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);

        return View('notes.note_edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $note->update($request->only('title', 'text', 'visibility'));

        if ($request->has('file')) {
            $note->attachments()->create([
                'file_name' => Attachment::saveFile($request->file('file')),
                'file_original_name' => $request->file('file')->getClientOriginalName(),
                'ext' =>  $request->file('file')->getClientOriginalExtension(),
            ]);
        }

        if ($request->has('delete_attachments')) {
            $id_attachments = $request->get('delete_attachments');
            foreach ($id_attachments as $id) {
                /** @var Attachment $attachment */
                $attachment = $note->attachments()->where('id', $id)->first();

                if($attachment) {
                    $attachment->deleteFile();
                }
            }
        }

        return redirect(route('notes.show', $note->uuid));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function destroy(Note $note)
    {
        $this->authorize('destroy', $note);

        $note->delete();

        return redirect('/dashboard');
    }

    /**
     *
     * @param DetachAttachmentRequest $request
     * @param Note $note
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function detachFile(DetachAttachmentRequest $request, Note $note)
    {
        $this->authorize('detach_file', $note);

        /** @var Attachment $attachment */
        $attachment = $note->attachments()->where('id', $request->get('id'))->first();

        if ($attachment) {
            $attachment->deleteFile();
        }

        return redirect(route('notes.show', $note->uuid));
    }

    /**
     *
     * @param ViewNoteRequest $request
     * @param Note $note
     * @return RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function share(ViewNoteRequest $request, Note $note)
    {
        $this->authorize('share', $note);

        $email = $request->get('email');

        $note->share(User::whereEmail($email)->first());
        $request->session()->flash('success', 'You have opened access for email ' . $email);

        return redirect(route('notes.show', $note->uuid));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function notesSharedYou()
    {
        return View('notes_shared_you');
    }

    /**
     *
     * @param DownloadAttachmentRequest $request
     * @param Note $note
     * @return string
     * @throws AuthorizationException
     */
    public function download(DownloadAttachmentRequest $request, Note $note): string
    {
        $this->authorize('download', $note);

        /** @var Attachment $attachment */
        $attachment = $note->attachments()->where('id', $request->get('id'))->first();
         if($attachment->doesntExist()){
            return  response()->download($attachment->filePath(), $attachment->file_original_name);
        }
        return $this->error('file does not exist', 422);
    }
}
