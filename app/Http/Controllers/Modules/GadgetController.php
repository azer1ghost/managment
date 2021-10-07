<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\GadgetRequest;
use App\Models\Gadget;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GadgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Gadget::class, 'gadget');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('panel.pages.gadgets.index')
            ->with([
                'gadgets' => Gadget::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%".$search."%"))
                    ->simplePaginate(5)
            ]);
    }

    public function create()
    {
        return view('panel.pages.gadgets.edit')
            ->with([
                'action' => route('gadgets.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(GadgetRequest $request)
    {
        dd($request->validated());
        $validated = $request->validated();
        $validated['status'] = $request->has('status');

        $gadget = Gadget::create($validated);

        return redirect()
            ->route('gadgets.edit', $gadget)
            ->withNotify('success', $gadget->getAttribute('name'));
    }

    public function show(Gadget $gadget)
    {
        return view('panel.pages.gadgets.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $gadget
            ]);
    }

    public function edit(Gadget $gadget)
    {
        return view('panel.pages.gadgets.edit')
            ->with([
                'action' => route('gadgets.update', $gadget),
                'method' => "PUT",
                'data' => $gadget
            ]);
    }

    public function update(GadgetRequest $request, Gadget $gadget)
    {
        $validated = $request->validated();
        $validated['status'] = $request->has('status');

        $gadget->update($validated);

        return back()->withNotify('info', $gadget->getAttribute('name'));
    }

    public function destroy(Gadget $gadget)
    {
        if ($gadget->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
