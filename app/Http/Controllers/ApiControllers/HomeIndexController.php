<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Scopes\NoteQuery;
use Illuminate\Http\Request;

class HomeIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $notes = NoteQuery::search(
            NoteQuery::published(Note::query()), $search
        )->with(['user'])->latest()->paginate(10);
        return NoteResource::collection($notes);
    }

}
