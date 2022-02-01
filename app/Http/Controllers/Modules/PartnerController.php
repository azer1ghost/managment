<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Partner::class, 'partner');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('pages.partners.index')->with([
            'partners' => Partner::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('pages.partners.edit')->with([
            'action' => route('partners.store'),
            'method' => 'POST',
            'data' => new Partner(),
        ]);
    }

    public function store(PartnerRequest $request): RedirectResponse
    {
        $partner = Partner::create($request->validated());

        return redirect()
            ->route('partners.edit', $partner)
            ->withNotify('success', $partner->getAttribute('name'));
    }

    public function show(Partner $partner)
    {
        return view('pages.partners.edit')->with([
            'action' => route('partners.store', $partner),
            'method' => null,
            'data' => $partner,
        ]);
    }

    public function edit(Partner $partner)
    {
        return view('pages.partners.edit')->with([
            'action' => route('partners.update', $partner),
            'method' => 'PUT',
            'data' => $partner,
        ]);
    }

    public function update(PartnerRequest $request, Partner $partner): RedirectResponse
    {
        $partner->update($request->validated());

        return redirect()
            ->route('partners.edit', $partner)
            ->withNotify('success', $partner->getAttribute('name'));
    }

    public function destroy(Partner $partner)
    {
        if ($partner->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
