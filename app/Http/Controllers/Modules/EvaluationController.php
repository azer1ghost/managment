<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Evaluation::class, 'evaluation');
    }
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('pages.evaluations.index')->with([
            'evaluations' => Evaluation::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('pages.evaluations.edit')->with([
            'action' => route('evaluations.store'),
            'method' => 'POST',
            'data' => new Evaluation(),
        ]);
    }

    public function store(EvaluationRequest $request): RedirectResponse
    {
        $evaluation = Evaluation::create($request->validated());

        return redirect()
            ->route('evaluations.edit', $evaluation)
            ->withNotify('success', $evaluation->getAttribute('name'));
    }

    public function show(Evaluation $evaluation)
    {
        return view('pages.evaluations.edit')->with([
            'action' => route('evaluations.store', $evaluation),
            'method' => null,
            'data' => $evaluation,
        ]);
    }

    public function edit(Evaluation $evaluation)
    {
        return view('pages.evaluations.edit')->with([
            'action' => route('evaluations.update', $evaluation),
            'method' => 'PUT',
            'data' => $evaluation,
        ]);
    }

    public function update(EvaluationRequest $request, Evaluation $evaluation): RedirectResponse
    {
        $evaluation->update($request->validated());

        return redirect()
            ->route('evaluations.edit', $evaluation)
            ->withNotify('success', $evaluation->getAttribute('name'));
    }

    public function destroy(Evaluation $evaluation)
    {
        if ($evaluation->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
