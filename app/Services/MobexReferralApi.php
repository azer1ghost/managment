<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobexReferralApi
{
    private string  $apiUrl = 'http://api.mobex.az/v1/referral/bonus', $token;
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
        return Http::withHeaders(['Authorization' => "Bearer $this->token", 'Accept' => 'application/json'])->get($this->apiUrl, [
           'key' => $this->key,
       ]);
    }
}