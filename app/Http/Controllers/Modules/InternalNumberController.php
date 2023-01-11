<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\InternalNumberRequest;
use App\Http\Controllers\Controller;
use App\Models\InternalNumber;
use Illuminate\Http\Request;
use App\Models\User;

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
            ->with([ 'internalNumbers' => InternalNumber::with('users')->when($search, fn ($query) => $query
                    ->whereHas('users',fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orwhere('name', 'like', "%".$search."%")
                    ->orwhereHas('users',fn($q) => $q->where('phone', 'like', "%$search%"))
                    ->orWhere('detail', 'like', "%".$search."%"))
                    ->paginate(25)
            ]);
    }
    public function cooperative(Request $request)
    {
        $search = $request->get('search');

        return view('pages.cooperative-numbers.index')
            ->with([ 'cooperativeNumbers' => User::isActive()
                ->where('is_partner', 0)
                ->when($search, fn ($query) => $query
                ->where('name', 'like', "%".$search."%")
                ->orWhere('surname', 'like', "%".$search."%")
                ->orWhere('phone', 'like', "%".$search."%")
                ->orWhere('email', 'like', "%".$search."%"))
                ->orderBy('order')
                ->get()
            ]);
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
