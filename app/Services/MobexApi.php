<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobexApi
{
    protected string $apiUrl = "http://api.mobex.az/v1/user/search";

    protected string $token = "884h7d345";

    protected string $key;
    protected string $value;

    public function by($key): MobexApi
    {
        $this->key = $key;

        return $this;
    }

    public function value($value): MobexApi
    {
        switch ($this->key){
            case 'customer_id':
                $this->value = $this->byClientCode($value);
                break;
            case 'phone':
                $this->value = phone_formatter($value);
                break;
            default:
                $this->value = $value;
        }

        return $this;
    }

    public function byClientCode($value): string
    {
        $client_code = strtoupper($value);

        $prefix = 'MBX';

        return str_starts_with($client_code, $prefix) ? $client_code : $prefix.$value;
    }

    public function get()
    {
       $response = Http::get($this->apiUrl, [
           'token' => $this->token,
           'value' => $this->value,
           'key' => $this->key,
       ])->json();
        if(!isset($response['errors'])) {
            $response['fullname'] = $response['full_name'];
        }
        return $response;
    }
}