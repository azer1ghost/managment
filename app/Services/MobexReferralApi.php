<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobexReferralApi
{
    protected string  $apiUrl = 'http://10.10.11.26:8000/api/referral/bonus', $token  = '';
    // 10.10.11.26
    // http://api.mobex.az/v1/referral/bonus

    public string $key = '';

    public function by($key): MobexReferralApi
    {
        $this->key = $key;

        return $this;
    }

    public function get()
    {
        return  Http::get($this->apiUrl, [
//           'token' => $this->token,
           'key' => $this->key,
       ])->json();
//        return $data = [
//            'total' => 10,
//            'efficiency' => 50,
//            'total_earnings' => 125,
//            'total_packages' => 15,
//            'bonus' => 5,
//        ];
    }
}