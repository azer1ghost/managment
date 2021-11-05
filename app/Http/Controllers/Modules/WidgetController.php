<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\WidgetRequest;
use App\Models\Widget;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Widget::class, 'widget');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('panel.pages.widgets.index')
            ->with([
                'widgets' => Widget::query()
                    ->when($search, fn ($query) => $query->where('key', 'like', "%".$search."%"))
                    ->oldest('order')->simplePaginate(5)
            ]);
    }

    public function create()
    {
        return view('panel.pages.widgets.edit')
            ->with([
                'action' => route('widgets.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(WidgetRequest $request)
    {
        $validated = $request->validated();
        $this->translates($validated);
        $validated['status'] = $request->has('status');

        $widget = Widget::create($validated);

        return redirect()
            ->route('widgets.edit', $widget)
            ->withNotify('success', $widget->getAttribute('key'));
    }

    public function show(Widget $widget)
    {
        return view('panel.pages.widgets.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $widget
            ]);
    }

    public function edit(Widget $widget)
    {
        return view('panel.pages.widgets.edit')
            ->with([
                'action' => route('widgets.update', $widget),
                'method' => "PUT",
                'data' => $widget
            ]);
    }

    public function update(WidgetRequest $request, Widget $widget)
    {
        $validated = $request->validated();
        $this->translates($validated);
        $validated['status'] = $request->has('status');

        $widget->update($validated);

        return back()->withNotify('info', $widget->getAttribute('key'));
    }

    public function destroy(Widget $widget)
    {
        if ($widget->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
