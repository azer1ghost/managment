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
        $user = User::isActive()->get('id');

        return view('pages.referrals.index', [
            'referrals' => Referral::with('user')
                ->when($search, fn($query) => $query->where('key', 'LIKE', "%$search%"))->whereIn('user_id', $user)
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
