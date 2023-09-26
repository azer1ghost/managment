<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandRequest;
use App\Http\Requests\FundRequest;
use App\Models\Change;
use App\Models\Command;
use App\Models\Company;
use App\Models\Fund;
use Illuminate\Http\Request;
use App\Models\User;

class FundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Fund::class, 'fund');
    }

    public function index(Request $request)
    {
        $company = $request->get('company_id');
        $search = $request->get('search');

        return view('pages.funds.index')
            ->with([
                'users' => User::isActive()->get(['id', 'name', 'surname']),
                'companies' => Company::get(['id', 'name']),
                'funds' => Fund::when($search, fn ($query) => $query
                    ->where('club', 'like', "%".$search."%"))
                    ->paginate(25)
            ]);
    }

    public function create(Request $request)
    {
        return view('pages.funds.edit')->with([
            'action' => route('funds.store'),
            'method' => 'POST',
            'data' => new Fund(),
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function store(FundRequest $request)
    {
        $fund = Fund::create($request->validated());
//        $fund->sync($request->get('main_activity'));

        return redirect()
            ->route('funds.edit', $fund)
            ->withNotify('success', $fund->getAttribute('id'));
    }

    public function show(Fund $fund)
    {
        return view('pages.funds.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $fund,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function edit(Fund $fund)
    {
        return view('pages.funds.edit')->with([
            'action' => route('funds.update', $fund),
            'method' => 'PUT',
            'data' => $fund,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function update(FundRequest $request, Fund $fund)
    {
        $fund->update($request->validated());
        $fund->users()->sync($request->get('users'));

        return redirect()
            ->route('funds.edit', $fund)
            ->withNotify('success', $fund->getAttribute('name'));
    }

    public function destroy(Fund $fund)
    {
        if ($fund->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
