<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Requests\StatementRequest, Controllers\Controller};
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyStatement;
use Illuminate\Http\RedirectResponse;
use App\Models\Statement;
use App\Models\User;

class StatementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Statement::class, 'statement');
    }

    public function index()
    {
        return view('pages.statements.index')->with([
           'statements' => Statement::paginate(10),
        ]);
    }

    public function create()
    {
        return view('pages.statements.edit')->with([
            'action' => route('statements.store'),
            'method' => null,
            'data' => null,
        ]);
    }

    public function store(StatementRequest $request): RedirectResponse
    {
        $user = User::all();
        $validator = $request->validated();
        $statement = Statement::create($validator);
        Notification::send($user, new NotifyStatement($request->title));

        return redirect()
            ->route('statements.edit', $statement)
            ->withNotify('success', $statement->getAttribute('title'));
    }

    public function show(Statement $statement)
    {
        return view('pages.statements.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $statement,
        ]);
    }

    public function edit(Statement $statement)
    {
        return view('pages.statements.edit')->with([
            'action' => route('statements.update', $statement),
            'method' => 'PUT',
            'data' => $statement,
        ]);
    }

    public function update(StatementRequest $request, Statement $statement): RedirectResponse
    {
        $statement->update($request->validated());

        return redirect()
            ->route('statements.edit', $statement)
            ->withNotify('success', $statement->getAttribute('title'));
    }

    public function destroy(Statement $statement)
    {
        if ($statement->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
