<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class ReferralBonusController extends Controller
{
    public function index()
    {
        return view('panel.pages.bonuses.index');
    }

    public function refresh()
    {
        $data = [
            'total' => 10,
            'efficiency' => 5,
            'total_earnings' => 125,
            'bonus' => 5,
        ];

        $referral = auth()->user()->referral()->update($data);

        return view('panel.pages.bonuses.index')->with(['referral' => $referral]);
    }
}