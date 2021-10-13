<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralRequest;
use App\Models\Referral;
use App\Services\MobexReferralApi;

class ReferralBonusController extends Controller
{
    public function index()
    {
        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first() ?? new Referral()]);
    }

    public function referral()
    {
        return auth()->user()->referral();
    }

    public function generate(ReferralRequest $request)
    {
        $referral = $this->referral()->create($request->validated());
        return back()->withNotify('success', $referral->getAttribute('key'));
    }

    public function refresh()
    {
        $data = (new MobexReferralApi)->by(
            $this->referral()->first()->getAttribute('key')
        )->get();

        dd($data);

        $this->referral()->update($data);

        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first()]);
    }
}