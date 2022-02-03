<?php


namespace App\View\Components;


use App\Models\Note;
use App\Models\User;
use App\Scopes\NoteQuery;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Notes extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return Factory|View
     */
    public function render()
    {
        /** @var User $user */
        $user=auth()->user();
        $search = request()->get('search');
        $notes = NoteQuery::search(
            Note::query(), $search                   //Note::query()    возможность добавлять квери параметры в запрос
        )->where('user_id', $user->id)->with(['user'])->latest()->paginate(10);
        return view('notes.notes', compact('notes'));
    }
}

