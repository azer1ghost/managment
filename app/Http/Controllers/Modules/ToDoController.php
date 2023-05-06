<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\ToDo;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    public function index()
    {
        return view('pages.notes.index')->with([
            'todos' => ToDo::get(),
        ]);
    }

    public function sendToDo(Request $request)
    {
        $todo = new ToDo();
        $todo->content = $request->input('content');
        $todo->user_id = auth()->user()->id;
        $todo->save();
        return response()->json(['success' => true, 'todo' => $todo]);
    }

    public function updateToDo(Request $request)
    {
        $todo = ToDo::where('id', $request->get('id'))->first();
        $is_checked = $request->get('is_checked') === 'true' ? 1 : 0;
        $todo->setAttribute('is_checked', $is_checked);
        $todo->save();
        return response()->json(['success' => true, 'todo' => $todo]);
    }

    public function deleteToDo(Request $request)
    {
        $todo = todo::where('id', $request->get('id'))->first();
        if ($todo->delete()) {
            return response('OK');
        }
        return response()->json(['success' => true, 'todo' => $todo]);
    }

    public function getToDo(Request $request)
    {
        $todos = Todo::where('user_id', auth()->id())->latest()->get();
        return response()->json($todos);
    }

}
