<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\GadgetRequest;
use App\Models\Gadget;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class GadgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Gadget::class, 'gadget');
    }

    public function index()
    {
        return view('panel.pages.gadgets.index')
            ->with([
                'gadgets' => Gadget::simplePaginate(10)
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

    public function store(GadgetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->file('logo')) {

            $image = $request->file('logo');

            $validated['logo'] = $image->storeAs('logos', $image->hashName());
        }

        $gadget = Gadget::create($validated);

        // Add social networks
        if($request->has('socials')){
            $gadget->socials()->createMany($validated['socials']);
        }

        return redirect()
            ->route('gadgets.index')
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

    public function update(GadgetRequest $request, Gadget $gadget): RedirectResponse
    {
        $validated = $request->validated();

        $validated['is_inquirable'] = $request->has('is_inquirable');

        if ($request->file('logo')) {

            $image = $request->file('logo');

            $validated['logo'] = $image->storeAs('logos', $image->hashName());

            if (Storage::exists($gadget->getAttribute('logo'))) {
                Storage::delete($gadget->getAttribute('logo'));
            }
        }

        $gadget->update($validated);

        // Add, update or delete social networks
        $socials = collect($request->get('socials') ?? []);

        // destroy should appear before create or update
        Social::destroy($gadget->socials()->pluck('id')->diff($socials->pluck('id')));

        $socials->each(fn($social) => $gadget->socials()->updateOrCreate(['id' => $social['id']], $social));

        return back()->withNotify('info', $gadget->getAttribute('name'));
    }

    public function destroy(Gadget $gadget)
    {
        if ($gadget->delete()) {
            if (Storage::exists($gadget->getAttribute('logo'))) {
                Storage::delete($gadget->getAttribute('logo'));
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
