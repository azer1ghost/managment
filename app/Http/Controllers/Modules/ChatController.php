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
        $id = Auth::id();
        $users = DB::select("
            SELECT u.id, u.name, u.surname, u.avatar, u.email, 
                SUM(IF(c.is_read = 0 AND c.`to` = $id, 1, 0)) AS unread, 
                MAX(c.created_at) AS last_message_date
            FROM users u
            LEFT JOIN chats c ON u.id = c.to OR u.id = c.from
            WHERE u.id != $id AND u.disabled_at IS NULL
            GROUP BY u.id, u.name, u.surname, u.avatar, u.email
            ORDER BY last_message_date DESC
        ");

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
