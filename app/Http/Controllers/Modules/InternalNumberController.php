<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternalNumberRequest;
use App\Models\Department;
use App\Models\InternalNumber;
use App\Models\InternalRelation;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class InternalNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(InternalNumber::class, 'internal_number');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('pages.internal_numbers.index')
            ->with([
                'internalNumbers' => InternalNumber::when($search,
                        fn ($query) => $query->where('name', 'like', "%".$search."%")
                            ->orWhere('phone', 'like', "%".$search."%")
                            ->orWhere('detail', 'like', "%".$search."%"))
                            ->paginate(25)]);
    }
    public function cooperative()
    {
        return view('pages.cooperative-numbers.index')
            ->with([ 'cooperativeNumbers' => User::isActive()->where('is_partner', 0)->orderBy('order')
                ->get(['phone_coop', 'name', 'surname', 'position_id'])]);

    }

    public function create()
    {
        return view('pages.internal_numbers.edit')->with([
            'action' => route('internal-numbers.store'),
            'method' => null,
            'data' => new InternalNumber(),
            'users' => User::get(['id', 'name', 'surname']),
        ]);
    }

    public function store(InternalNumberRequest $request)
    {
        $internalNumber = InternalNumber::create($request->validated());

        return redirect()
            ->route('internal-numbers.edit', $internalNumber)
            ->withNotify('success', $internalNumber->getAttribute('name'));
    }

    public function show(InternalNumber $internalNumber)
    {
        return view('pages.internal_numbers.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $internalNumber,
            'users' => User::get(['id', 'name', 'surname']),
        ]);
    }

    public function edit(InternalNumber $internalNumber)
    {
        return view('pages.internal_numbers.edit')->with([
            'action' => route('internal-numbers.update', $internalNumber),
            'method' => 'PUT',
            'data' => $internalNumber,
            'users' => User::get(['id', 'name', 'surname']),
        ]);
    }

    public function update(InternalNumberRequest $request, InternalNumber $internalNumber)
    {
        $internalNumber->update($request->validated());

        return redirect()
            ->route('internal-numbers.edit', $internalNumber)
            ->withNotify('success', $internalNumber->getAttribute('name'));
    }

    public function destroy(InternalNumber $internalNumber)
    {
        if ($internalNumber->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
