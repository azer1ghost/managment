<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandRequest;
use App\Models\Change;
use App\Models\Command;
use Illuminate\Http\Request;
use App\Models\User;

class CommandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Command::class, 'command');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('pages.commands.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'commands' => Command::when($search, fn($query) => $query
                    ->where('description', 'like', "%" . $search . "%"))
                    ->orderByDesc('command_date')
                    ->paginate(25)]);
    }

    public function create()
    {
        return view('pages.commands.edit')->with([
            'action' => route('commands.store'),
            'method' => 'POST',
            'data' => new Command(),
            'users' => User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function store(CommandRequest $request)
    {
        $command = Command::create($request->validated());
        $command->users()->sync($request->get('users'));

        return redirect()
            ->route('commands.edit', $command)
            ->withNotify('success', $command->getAttribute('executor'));
    }

    public function show(Change $command)
    {
        return view('pages.commands.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $command,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function edit(Command $command)
    {
        return view('pages.commands.edit')->with([
            'action' => route('commands.update', $command),
            'method' => 'PUT',
            'data' => $command,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
        ]);
    }

    public function update(CommandRequest $request, Command $command)
    {
        $command->update($request->validated());
        $command->users()->sync($request->get('users'));

        return redirect()
            ->route('commands.edit', $command)
            ->withNotify('success', $command->getAttribute('name'));
    }

    public function destroy(Command $command)
    {
        if ($command->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
