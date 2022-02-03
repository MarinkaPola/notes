<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class NotesShared extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return Factory|View
     */
    public function render()
    {
        /** @var User $user */
      $user = auth()->user();
       $notes = $user->user_notes()->latest()->paginate();
        return View('notes.notes',compact('notes'));
    }
}
