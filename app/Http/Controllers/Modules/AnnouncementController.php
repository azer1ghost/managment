<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;
use App\Models\User;
use App\Traits\Permission;
use Illuminate\Http\RedirectResponse;

class AnnouncementController extends Controller
{
    use Permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Announcement::class, 'announcement');
    }

    public function index()
    {
        return view('panel.pages.announcements.index')->with([
            'announcements' => Announcement::paginate(10)
        ]);
    }

    public function create()
    {
        return view('panel.pages.announcements.edit')->with([
            'action' => route('announcements.store'),
            'method' => 'POST',
            'data' => new Announcement(),
            'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),

        ]);
    }
    public function store(AnnouncementRequest $request): RedirectResponse
    {
        $validated =  $request->validated();

        $validated['users'] = implode("," , $validated['users']);
        $validated['status'] = $request->has('status');
        $this->permissions($validated, new Announcement());

        $announcement = Announcement::create($validated);

        return redirect()
            ->route('announcements.edit', $announcement)
            ->withNotify('success', $announcement->getAttribute('key'));
    }

    public function show(Announcement $announcement)
    {
        return view('panel.pages.announcements.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $announcement,
            'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return view('panel.pages.announcements.edit')->with([
            'action' => route('announcements.update', $announcement),
            'method' => 'PUT',
            'data' => $announcement,
            'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
        ]);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $validated =  $request->validated();

        $validated['users'] = implode("," , $validated['users']);
        $validated['status'] = $request->has('status');
        $this->permissions($validated, $announcement);

        $announcement->update($validated);

        return redirect()
            ->route('announcements.edit', $announcement)
            ->withNotify('success', $announcement->getAttribute('key'));
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->delete()) {
            return response('OK');
        }

        return response()->setStatusCode('204');
    }
}
