<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralRequest;
use App\Services\MobexReferralApi;

class ReferralBonusController extends Controller
{
    public function index()
    {
        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first()]);
    }

    public function referral()
    {
        return auth()->user()->referral();
    }

    public function create(ReferralRequest $request)
    {
        $referral = $this->referral()->create($request->validated());
        return back()->withNotify('success', $referral->getAttribute('key'));
    }

    public function refresh()
    {
        $data = (new MobexReferralApi)->by(
            $this->referral()->first()->getAttribute('key')
        )->get();

        $this->referral()->update($data);

        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first()]);
    }
}