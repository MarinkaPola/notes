<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class Relationship implements Rule
{

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
         //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $note_id = request()->route('uuid')->id;

        /** @var User $targetUser */
        $targetUser = User::where('email', request()->get('email'))->first();
        if ($targetUser->user_notes()->where('note_id',  $note_id)->doesntExist()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'You have already shared this';
    }
}
