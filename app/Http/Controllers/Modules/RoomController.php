<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\RoomRequest;
use App\Models\Company;
use App\Models\Room;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Room::class, 'room');
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
            'action' => route('rooms.store'),
            'method' => null,
            'data' => new Room(),
            'companies' => Company::get(['id','name']),
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
        return view('pages.rooms.edit')->with([
            'action' => route('rooms.update', $room),
            'method' => 'PUT',
            'data' => $room,
            'companies' => Company::get(['id','name']),
        ]);
    }

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

}
