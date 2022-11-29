<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Services\FirebaseApi;
use App\Models\{Chat, User};
use Illuminate\Http\Request;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::isActive()
            ->where('id', '!=', auth()->id())
            ->orderByDesc('order')
            ->get();

        $recentUsers = User::query()
            ->isActive()
            ->join('chats', 'chats.to', '=', 'users.id')
            ->where('from',  '=', auth()->id())
            ->orderBy('chats.is_read')
            ->select('users.*')
            ->get()
            ->unique('name');

        return view('pages.chats.index')->with([
            'users' => $users,
            'recentUsers' => $recentUsers,
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
