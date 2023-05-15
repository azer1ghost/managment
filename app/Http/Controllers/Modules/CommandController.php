<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandRequest;
use App\Models\Change;
use App\Models\Command;
use App\Models\Company;
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
        $limit = $request->get('limit',25);
        $company = $request->get('company_id', 3);

        $commands = Command::when($search, fn($query) => $query
            ->where('content', 'like', "%" . $search . "%"))->when($company, fn($query) => $query
            ->where('company_id', $company))
            ->orderBy('ordering');

        if(is_numeric($limit)) {
            $commands = $commands->paginate($limit);
        }else {
            $commands = $commands->get();
        }

        return view('pages.commands.index')
            ->with(['users' => User::get(['id', 'name', 'surname']),
                'commands' => $commands,
                'companies' => Company::get(['id','name']),
            ]);
    }

    public function create(Request $request)
    {
        if ($request->get('id')) {

            $data = Command::whereId($request->get('id'))->first();
        }
        else {
            $data = new Command();
        }
        return view('pages.commands.edit')->with([
            'action' => route('commands.store'),
            'method' => 'POST',
            'data' => $data,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
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
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(Command $command)
    {
        return view('pages.commands.edit')->with([
            'action' => route('commands.update', $command),
            'method' => 'PUT',
            'data' => $command,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
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
    public function sortable(Request $request)
    {
        foreach ($request->get('item') as $key => $value) {
            $command = Command::find($value);
            $command->ordering = $key;
            $command->save();
        }
    }
}
