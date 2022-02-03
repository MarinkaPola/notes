<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
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
        return ($user && $user->id === $note->user_id) || $note->visibility === 1;
    }
    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Note $note
     * @return mixed
     */
    public function show(?User $user, Note $note): bool
    {
        if ($note->isPublic()) {
            return true;
        }
        if ($user && ($note->hasAuthor($user) || $note->sharedWith($user))) {
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
    public function update(User $user, Note $note): bool
    {
        if ($note->hasAuthor($user) ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function detachFile(User $user, Note $note): bool
    {
        if ($note->hasAuthor($user)  ) {
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
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(): bool
    {
            return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return mixed
     */
    public function notesSharedYou(User $user): bool
    {
        if ($user->user_notes()->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function share(User $user, Note $note)
    {
            return $note->hasAuthor($user);
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
