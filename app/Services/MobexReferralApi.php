<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobexReferralApi
{
    protected string $apiUrl = "#";
    // http://api.mobex.az/v1/referral/bonus?token=ffsdf&key=dsfsf
    protected string $token = "#";

    protected string $key;
    protected string $value;

    public function by($key): MobexReferralApi
    {
        $this->key = $key;

        return $this;
    }

    public function value($value): MobexReferralApi
    {
        $this->value = $value;

        return $this;
    }

    public function get()
    {
//        return $response = Http::get($this->apiUrl, [
//           'token' => $this->token,
//           'key' => $this->key,
//       ])->json();
        return $data = [
            'total' => 10,
            'efficiency' => 5,
            'total_earnings' => 125,
            'total_packages' => 15,
            'bonus' => 5,
        ];
    }
}