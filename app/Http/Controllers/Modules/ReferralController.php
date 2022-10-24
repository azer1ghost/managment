<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralRequest;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Referral::class, 'referral');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);
        $user = $request->get('user_id');
        $users = User::isActive()->get(['id', 'name', 'surname']);

        return view('pages.referrals.index', [
            'users' => $users,
            'referrals' => Referral::with('user')
                ->when($search, fn($query) => $query->where('key', 'LIKE', "%$search%"))
                ->when($user, fn($query)=> $query->where('user_id', $user))
                ->whereIn('user_id', $users)
                ->paginate($limit)
        ]);
    }

    public function store(ReferralRequest $request)
    {
        $validated = $request->validated();

        $referral = Referral::create($validated);

        return redirect()
            ->route('referrals.edit', $referral)
            ->withNotify('success', $referral->getAttribute('key'));
    }

    public function show(Referral $referral)
    {
        return view('pages.referrals.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data'   => $referral,
                'users'  => User::get(['id', 'name', 'surname'])
            ]);
    }

    public function edit(Referral $referral)
    {
        return view('pages.referrals.edit')
            ->with([
                'action' => route('referrals.update', $referral),
                'method' => 'PUT',
                'data'   => $referral,
                'users'  => User::get(['id', 'name', 'surname'])
            ]);
    }

    public function update(ReferralRequest $request, Referral $referral)
    {
        $validated = $request->validated();

        $referral->update($validated);

        return redirect()
            ->route('referrals.edit', $referral)
            ->withNotify('success', $referral->getAttribute('key'));
    }

    public function destroy(Referral $referral)
    {
        if ($referral->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
