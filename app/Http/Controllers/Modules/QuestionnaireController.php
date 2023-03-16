<?php

namespace App\Http\Controllers\Modules;

use App\Http\{Controllers\Controller, Requests\QuestionnaireRequest};
use Illuminate\Http\{RedirectResponse, Request};
use App\Models\{Questionnaire, Client};

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Questionnaire::class, 'questionnaire');
    }

    public function index(Request $request)
    {
        $client = $request->get('client');
        return view('pages.questionnaires.index')->with([
            'questionnaires' => Questionnaire::when($client, fn ($q) => $q->where('client_id', $client))
        ->paginate(25),
            'clients' => Client::get()
        ]);
    }

    public function create()
    {
        return view('pages.questionnaires.edit')->with([
            'action' => route('questionnaires.store'),
            'method' => 'POST',
            'data' => new Questionnaire(),
            'customses' => Questionnaire::customses(),
            'sources' => Questionnaire::sources(),
            'clients' => Client::get(['id', 'fullname', 'voen']),
        ]);
    }

    public function store(QuestionnaireRequest $request)
    {
        $validated = $request->validated();
        if ($request->customs) {
            $validated['customs'] = implode(",", $request->customs);
        }
        if ($request->source) {
            $validated['source'] = implode(",", $request->source);
        }
        $validated['send_email'] = $request->has('send_email');

        $questionnaire = Questionnaire::create($validated);
        return redirect()
            ->route('questionnaires.edit', $questionnaire)
            ->withNotify('success', $questionnaire->getAttribute('name'));
    }

    public function show(Questionnaire $questionnaire)
    {
        return view('pages.questionnaires.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $questionnaire,
            'customses' => Questionnaire::customses(),
            'sources' => Questionnaire::sources(),
            'clients' => Client::get(['id', 'fullname', 'voen']),
        ]);
    }

    public function edit(Questionnaire $questionnaire)
    {
        return view('pages.questionnaires.edit')->with([
            'action' => route('questionnaires.update', $questionnaire),
            'method' => 'PUT',
            'data' => $questionnaire,
            'customses' => Questionnaire::customses(),
            'sources' => Questionnaire::sources(),
            'clients' => Client::get(['id', 'fullname', 'voen']),
        ]);
    }

    public function update(QuestionnaireRequest $request, Questionnaire $questionnaire): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->customs) {
            $validated['customs'] = implode(",", $request->customs);
        }
        if ($request->source) {
            $validated['source'] = implode(",", $request->source);
        }
        $validated['send_email'] = $request->has('send_email');
        $questionnaire->update($validated);

        return redirect()
            ->route('questionnaires.edit', $questionnaire)
            ->withNotify('success', $questionnaire->getAttribute('name'));
    }

    public function destroy(Questionnaire $questionnaire)
    {
        if ($questionnaire->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

}
