<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Note;
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

    public function updateNote(Request $request)
    {
        $note = Note::where('id', $request->get('id'))->first();
        $note->setAttribute('content', $request->get('content'));
        $note->save();
        return response()->json(['success' => true, 'note' => $note]);
    }

    public function deleteNote(Request $request)
    {
        $note = Note::where('id', $request->get('id'))->first();
        if ($note->delete()) {
            return response('OK');
        }
        return response()->json(['success' => true, 'note' => $note]);
    }

    public function getNote(Request $request)
    {
        $chats = Note::where('user_id', auth()->id())->latest()->get();
        return response()->json($chats);
    }

}
