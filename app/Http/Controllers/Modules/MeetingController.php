<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{

    public function index()
    {
        $meetings = Meeting::paginate(10);
        return view('panel.pages.meetings.index')->with([
           'meetings' => $meetings
        ]);
    }


    public function create()
    {
        return view('panel.pages.meetings.edit')->with([
            'action' => route('meetings.store'),
            'method' => null,
            'data' => null,
            'statuses' =>Meeting::statuses()
            ]);


    }


    public function store(MeetingRequest $request)
    {
        $validated = $request->validated();
        $meeting = Meeting::create($validated);

        return redirect()
            ->route('meetings.edit', $meeting)
            ->withNotify('success', $meeting->getAttribute('name'));
    }


    public function show(Meeting $meeting)
    {
        return view('panel.pages.meetings.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $meeting,
            'statuses' =>Meeting::statuses()

        ]);
    }


    public function edit(Meeting $meeting)
    {
        return view('panel.pages.meetings.edit')->with([
            'action' => route('meetings.update', $meeting),
            'method' => 'PUT',
            'data' => $meeting,
            'statuses' =>Meeting::statuses()

        ]);
    }


    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validated();
        $meeting->update($validated);

        return redirect()
            ->route('meetings.edit', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }


    public function destroy(Meeting $meeting)
    {
        if ($meeting->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
