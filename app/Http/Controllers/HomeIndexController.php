<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Scopes\NoteQuery;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $notes = NoteQuery::search(
            NoteQuery::published(Note::query()), $search
        )->with(['user'])->latest()->paginate(10);
        return View('welcome')->with('notes', $notes);
    }

}
