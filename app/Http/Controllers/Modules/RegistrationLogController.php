<?php

namespace App\Http\Controllers\Modules;

use App\Events\RegistrationLogCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationLogRequest;
use App\Models\Change;
use App\Models\Company;
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
        $company = $request->get('company_id', 3);
        $search = $request->get('search');
        return view('pages.registration-logs.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'registrationLogs' => RegistrationLog::when($company, fn($query) => $query
                    ->where('company_id', $company))
                    ->orderByDesc('arrived_at')
                    ->paginate(25)]);
    }

    public function create()
    {
        return view('pages.registration-logs.edit')->with([
            'action' => route('registration-logs.store'),
            'method' => 'POST',
            'data' => new RegistrationLog(),
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(RegistrationLogRequest $request)
    {
        $registrationLog = RegistrationLog::create($request->validated());
        event(new RegistrationLogCreated($registrationLog));

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
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(RegistrationLog $registrationLog)
    {
        return view('pages.registration-logs.edit')->with([
            'action' => route('registration-logs.update', $registrationLog),
            'method' => 'PUT',
            'data' => $registrationLog,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
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


    public function accepted(RegistrationLog $registrationLog)
    {
        if ($registrationLog->update(['received_at' => now()])) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
