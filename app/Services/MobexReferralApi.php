<?php
//
//namespace App\Services;
//
//use Illuminate\Support\Facades\Http;
//
//class MobexReferralApi
//{
//    private string $apiUrl = 'http://api.mobex.az/v1/referral/', $token;
//    public string $key = '';
//    public string $method = 'bonus';
//
//    public function __construct()
//    {
//        $this->token = sha1('hesoyam');
//    }
//
//    public function url($method): MobexReferralApi
//    {
//        $this->method = $method;
//
//        return $this;
//    }
//
//    public function by($key): MobexReferralApi
//    {
//        $this->key = $key;
//
//        return $this;
//    }
//
//    public function get()
//    {
//        $this->apiUrl .= $this->method;
//
//        return Http::withHeaders(['Authorization' => "Bearer $this->token", 'Accept' => 'application/json'])->get($this->apiUrl, [
//           'key' => $this->key,
//       ]);
//    }
//}