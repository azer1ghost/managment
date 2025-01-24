<?php

namespace App\Http\Controllers\Modules;

use App\Models\Rule;
use App\Http\{Controllers\Controller, Requests\RuleRequest};
use Illuminate\Http\Request;

class RuleController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(Rule::class, 'rule');
//    }

    public function index()
    {
        return view('pages.rules.index')
            ->with([
                'rules' => Rule::get()
            ]);
    }


    public function create()
    {
        return view('pages.rules.edit')->with([
            'action' => route('rules.store'),
            'method' => 'POST',
            'data' => new Rule(),
        ]);
    }

    public function store(RuleRequest $request)
    {
        $rule = Rule::create($request->validated());

        return redirect()
            ->route('rules.edit', $rule)
            ->withNotify('success', $rule->getAttribute('name'));
    }

    public function show(Rule $rule)
    {
        return view('pages.rules.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $rule,
        ]);
    }

    public function edit(Rule $rule)
    {
        return view('pages.rules.edit')->with([
            'action' => route('rules.update', $rule),
            'method' => 'PUT',
            'data' => $rule,
        ]);
    }

    public function update(RuleRequest $request, Rule $rule)
    {
        $validated = $request->validated();
        $rule->update($validated);

        return redirect()
            ->route('rules.edit', $rule)
            ->withNotify('success', $rule->getAttribute('name'));
    }

    public function destroy(Rule $rule)
    {
        if ($rule->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
