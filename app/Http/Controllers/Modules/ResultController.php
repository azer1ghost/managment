<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\ResultRequest;
use App\Models\Result;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;


class ResultController extends Controller
{
    public function store(ResultRequest $request, $modelId)
    {
        $validated = $request->validated();

        $model =  ("App\\Models\\" . $validated['model'])::find($modelId);

        $model->result()->create($validated);

        return back()->withNotify('success', 'Result');
    }

    public function update(ResultRequest $request, Result $result): RedirectResponse
    {
        $validated = $request->validated();

        $result->update($validated);

        return back()->withNotify('info', 'Result');
    }
}