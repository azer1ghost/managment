<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralRequest;
use App\Models\Referral;
use App\Services\MobexReferralApi;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['throttle:10,1'])->except(['index']);
    }

    public function index()
    {
        return view('panel.pages.bonuses.index')->with(['referral' => $this->referral()->first() ?? new Referral()]);
    }

    public function referral(): HasOne
    {
        return auth()->user()->referral();
    }

    public function generate(ReferralRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['key'] = strtolower(str_replace([' ', '-'] , '', \Str::slug($data['key'])));

        $referral = $this->referral()->create($data);

        return back()->withNotify('success', $referral->getAttribute('key'));
    }

    public function refresh()
    {
        $response = (new MobexReferralApi)->by(
            $this->referral()->first()->getAttribute('key')
        )->get();

        $data = $response->json();

        if($response->status() != 200){
            return back()->withNotify('error', "Error Code: {$response->status()}. " .  $response->toPsrResponse()->getReasonPhrase(), true);
        }

        if(array_key_exists('error', $data)){
            return back()->withNotify('error', 'Sizin hazırda referalınız yoxdur :(', true);
        }

        $referral = $this->referral()->first();

        $data['total_earnings'] += $referral->getAttribute('total_earnings');
        $data['total_packages'] += $referral->getAttribute('total_packages');
        $data['bonus'] = ($data['total_earnings'] * $referral->getAttribute('referral_bonus_percentage') / 100);

        if(array_key_exists('referral_bonus_percentage', $data)){
            unset($data['referral_bonus_percentage']);
        }

        $this->referral()->update($data);

        return back()->with(['referral' => $this->referral()->first()]);
    }

    public function refreshReferral(Request $request)
    {
        $referral = Referral::find($request->get('key'));

        $response = (new MobexReferralApi)->by($referral->key)->get();

        $data = $response->json();

        if($response->status() != 200){
            return back()->withNotify('error', "Error Code: {$response->status()}. " .  $response->toPsrResponse()->getReasonPhrase(), true);
        }

        if(array_key_exists('error', $data)){
            return back()->withNotify('error', 'Sizin hazırda referalınız yoxdur :(', true);
        }

        $data['total_earnings'] += $referral->getAttribute('total_earnings');
        $data['total_packages'] += $referral->getAttribute('total_packages');
        $data['bonus'] = ($data['total_earnings'] * $referral->getAttribute('referral_bonus_percentage') / 100);

        if(array_key_exists('referral_bonus_percentage', $data)){
            unset($data['referral_bonus_percentage']);
        }

        $referral->update($data);

        return back();
    }
}