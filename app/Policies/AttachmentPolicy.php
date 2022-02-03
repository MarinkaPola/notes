<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User|null $user
     * @param Note $note
     * @return mixed
     */
    public function viewAny(?User $user, Note $note): bool
    {
        return $user && $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function create(User $user, Note $note): bool
    {
        if ($note->hasAuthor($user)){
        return true;
    }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Note $note
     * @param Attachment $attachment
     * @return mixed
     */
    public function view(?User $user, Note $note, Attachment $attachment): bool
    {

        return $user && $user->id === $note->user_id && $note->id === $attachment->note_id;

    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function update(User $user, Note $note): bool
    {
        if ($note->hasAuthor($user) ) {
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function destroy (User $user, Note $note): bool
    {
        if ($note->hasAuthor($user) ) {
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Note $note
     * @return mixed
     */
    public function download(?User $user, Note $note): bool
    {
        if ($note->isPublic()) {
            return true;
        }
        if ($user && ($note->hasAuthor($user) || $note->sharedWith($user))) {
            return true;
        }
        return false;
    }
}
