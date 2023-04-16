<?php

namespace App\Http\Controllers\Modules;

use App\Events\ChatEvent;
use App\Events\RoomEvent;
use App\Http\Requests\RoomRequest;
use App\Models\Chat;
use App\Models\Company;
use App\Models\Room;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;

class RoomController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->authorizeResource(Room::class, 'room');
    }

    public function index()
    {
        return view('pages.rooms.index')
            ->with([
                'companies' => Company::get(['id','name']),
                'rooms' => Room::get()
            ]);
    }

    public function create()
    {
        return view('pages.rooms.edit')->with([
            'rooms' => Room::where('department_id', request()->get('department_id'))->latest()->limit(500)->get()->reverse(),

        ]);
    }

    public function store(RoomRequest $request)
    {
        $room = Room::create($request->validated());

        return redirect()->back();
    }

    public function show(RoomRequest $room)
    {
        return view('pages.rooms.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $room,
        ]);
    }

    public function edit(Room $room)
    {

    }
//   public function SendMessage(Request $request)
//    {
//        event(new RoomEvent($request->get('username'),$request->get('message'),$request->get('department') ));
//        return true;
//    }

    public function update(RoomRequest $request, Room $room)
    {
        $validated = $request->validated();
        $room->update($validated);

        return redirect()
            ->route('rooms.edit', $room)
            ->withNotify('success', $room->getAttribute('name'));
    }

    public function destroy(Room $room)
    {
        if ($room->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
    public function chatRoom(Request $request)
    {
        $user = auth()->user()->getAttribute('fullname');
        $message = $request->get('message');
        $department = $request->get('department_id');

        $data = new Room();
        $data->user = $user;
        $data->department_id = $department;
        $data->message = $message;

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

        $data = ['user' => $user, 'department_id' => $department, 'message' => $message];
        $pusher->trigger('room-chat', 'my-event', $data);
    }

    public function sendMessage(Request $request)
    {
        $user = auth('web')->user()->getAttribute('fullname');
        $message = $request->get('message');
        $department = $request->get('department_id');

//        event(new RoomEvent($user, $message, $department));
        $options = [
            'cluster' => 'mt1',
            'useTLS' => true
        ];
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'), $options
        );

        $data = ['user' => $user, 'department_id' => $department, 'message' => $message];
        $pusher->trigger('room', 'RoomEvent', $data);

        Room::create([
            'user' => $user,
            'department_id' => $request->get('department_id'),
            'message' => $message
        ]);

        return response()->json(['status' => 'success']);
    }
    public function getMessage()
    {
        $chats = Room::get();
        return response()->json($chats);
    }
}
