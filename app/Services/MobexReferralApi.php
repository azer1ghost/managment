<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobexReferralApi
{
    private string  $apiUrl = 'http://10.10.11.26:8000/api/referral/bonus', $token;
    // 10.10.11.26
    // http://api.mobex.az/v1/referral/bonus

    public string $key = '';

    public function __construct()
    {
        $this->token = sha1('hesoyam');
    }

    public function by($key): MobexReferralApi
    {
        $this->key = $key;

        return $this;
    }

    public function get()
    {
        return Http::withHeaders(['Authorization' => "Bearer $this->token"])->get($this->apiUrl, [
           'key' => $this->key,
       ]);
    }
}