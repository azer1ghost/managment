<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Services\FirebaseApi;
use App\Models\{Chat, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function index()
    {
        $users = DB::select("select users.id, users.name, users.surname, users.avatar, users.email, count(is_read) as unread 
        from users LEFT  JOIN  chats ON users.id = chats.from and is_read = 0 and chats.to = " . Auth::id() . "
        where users.id != " . Auth::id() . " and users.disabled_at IS NULL
        group by users.id, users.name, users.surname, users.avatar, users.email");
        return view('pages.chats.index')->with([
            'users' => $users,

        ]);

    }
    public function message($user_id)
    {
        $user = User::with('position')->find($user_id);
        $my_id = auth()->id();

        Chat::where(['from' => $user_id, 'to' => $my_id])->update(['is_read' => 1]);

        $messages = Chat::where(function ($query) use ($user_id, $my_id) {
            $query->where('from', $my_id)->where('to', $user_id);
//            $query->where('from', $user_id)->where('to', $my_id);
        })->orWhere(function ($query) use ($user_id, $my_id) {
            $query->where('from', $user_id)->where('to', $my_id);
        })->get();

        return view('pages.chats.messages')->with([
            'user' => $user ,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $from = auth()->id();
        $to = $request->reciever_id;
        $message = $request->get('message');

        $data = new Chat();
        $data->from = $from;
        $data->to = $to;
        $data->message = $message;
        $data->is_read = 0;

        $data->save();

        $options = [
            'cluster' => 'mt1',
            'useTLS' => true
        ];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'), $options
        );

        $data = ['from' => $from, 'to' => $to];
        $pusher->trigger('my-channel', 'my-event', $data);

    }
}
