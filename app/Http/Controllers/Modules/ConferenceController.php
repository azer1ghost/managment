<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Conference::class, 'conference');
    }

    public function index()
    {
        return view('pages.conferences.index')->with([
            'conferences' => Conference::paginate(10)
        ]);
    }

    public function create()
    {
        return view('pages.conferences.edit')->with([
            'action' => route('conferences.store'),
            'method' => null,
            'data' => null,
            'statuses' => Conference::statuses()
        ]);
    }

    public function store(ConferenceRequest $request): RedirectResponse
    {
       $conference = Conference::create($request->validated());

       return redirect()
           ->route('conferences.edit', $conference)
           ->withNotify('success', $conference->getAttribute('name'));
    }

    public function show(Conference $conference)
    {
        return view('pages.conferences.edit')->with([
            'action' => route('conferences.store', $conference),
            'method' => null,
            'data' => $conference,
            'statuses' => Conference::statuses()
        ]);
    }

    public function edit(Conference $conference)
    {
        return view('pages.conferences.edit')->with([
            'action' => route('conferences.update', $conference),
            'method' => 'PUT',
            'data' => $conference,
            'statuses' => Conference::statuses()
        ]);
    }

    public function update(ConferenceRequest $request, Conference $conference): RedirectResponse
    {
        $conference->update($request->validated());

        return redirect()
            ->route('conferences.edit', $conference)
            ->withNotify('success', $conference->getAttribute('name'));
    }

    public function destroy(Conference $conference)
    {
        if ($conference->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
