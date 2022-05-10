<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Models\Department;
use App\Models\Meeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Meeting::class, 'meeting');
    }

    public function index(Request $request)
    {
        $department  = $request->get('department');

        return view('pages.meetings.index')->with([
           'meetings' => Meeting::paginate(10),
        ]);
    }

    public function create()
    {
        return view('pages.meetings.edit')->with([
            'action' => route('meetings.store'),
            'method' => null,
            'data' => null,
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function store(MeetingRequest $request): RedirectResponse
    {
        $meeting = Meeting::create($request->validated());

        return redirect()
            ->route('meetings.edit', $meeting)
            ->withNotify('success', $meeting->getAttribute('name'));

    }

    public function show(Meeting $meeting)
    {
        return view('pages.meetings.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $meeting,
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function edit(Meeting $meeting)
    {
        return view('pages.meetings.edit')->with([
            'action' => route('meetings.update', $meeting),
            'method' => 'PUT',
            'data' => $meeting,
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function update(MeetingRequest $request, Meeting $meeting): RedirectResponse
    {
        $meeting->update($request->validated());

        return redirect()
            ->route('meetings.edit', $meeting)
            ->withNotify('success', $meeting->getAttribute('name'));
    }

    public function destroy(Meeting $meeting)
    {
        if ($meeting->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
