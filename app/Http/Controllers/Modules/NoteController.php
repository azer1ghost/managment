<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Department;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return view('pages.notes.index')->with([
           'notes' => Note::get(),
        ]);
    }

    public function sendNote(Request $request)
    {
        $note = new Note;
        $note->content = $request->input('content');
        $note->user_id = auth()->user()->id;
        $note->save();
        return response()->json(['success' => true, 'note' => $note]);
    }

}
