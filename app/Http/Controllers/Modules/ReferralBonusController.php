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
        $response = (new MobexReferralApi)->by(
            $this->referral()->first()->getAttribute('key')
        )->get();

        if($response->status() != 200){
            return back()->withNotify('error', "Error Code: {$response->status()}. " .  $response->toPsrResponse()->getReasonPhrase(), true);
        }

        if(array_key_exists('error', $response->json())){
            return back()->withNotify('error', 'Sizin hazırda referalınız yoxdur :(', true);
        }

        $data = $response->json();

        $referral = $this->referral()->first();

        $data['total_earnings'] += $referral->getAttribute('total_earnings');
        $data['total_packages'] += $referral->getAttribute('total_packages');
        $data['bonus'] = ($data['total_earnings'] * $referral->getAttribute('referral_bonus_percentage') / 100);

        if(array_key_exists('referral_bonus_percentage', $data)){
            unset($data['referral_bonus_percentage']);
        }

        $this->referral()->update($data);

        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first()]);
    }
}