<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationLogRequest;
use App\Models\Change;
use App\Models\RegistrationLog;
use Illuminate\Http\Request;
use App\Models\User;

class RegistrationLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(RegistrationLog::class, 'registration_log');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('pages.registration-logs.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'registrationLogs' => RegistrationLog::when($search, fn ($query) => $query
                    ->where('description', 'like', "%".$search."%"))
                    ->paginate(25)]);
    }

    public function create()
    {
        return view('pages.registration-logs.edit')->with([
            'action' => route('registration-logs.store'),
            'method' => 'POST',
            'data' => new RegistrationLog(),
            'users' =>User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function store(RegistrationLogRequest $request)
    {
        $registrationLog = RegistrationLog::create($request->validated());

        return redirect()
            ->route('registration-logs.edit', $registrationLog)
            ->withNotify('success', $registrationLog->getAttribute('sender'));
    }

    public function show(Change $registrationLog)
    {
        return view('pages.registration-logs.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $registrationLog,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function edit(RegistrationLog $registrationLog)
    {
        return view('pages.registration-logs.edit')->with([
            'action' => route('registration-logs.update', $registrationLog),
            'method' => 'PUT',
            'data' => $registrationLog,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function update(RegistrationLogRequest $request, RegistrationLog $registrationLog)
    {
        $registrationLog->update($request->validated());

        return redirect()
            ->route('registration-logs.edit', $registrationLog)
            ->withNotify('success', $registrationLog->getAttribute('name'));
    }

    public function destroy(RegistrationLog $registrationLog)
    {
        if ($registrationLog->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
