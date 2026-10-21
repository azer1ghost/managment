<?php

namespace App\Services\SmsProviders;

use Illuminate\Support\Facades\Http;

class PoctGoyercini
{
    protected ?string $user;
    protected ?string $password;
    protected ?string $to;
    protected string $message;
    private ?string $baseUrl;

    public function __construct($to = null, $message = null)
    {
        $this->to = $to;
        $this->message = $message;

        $this->baseUrl = config('services.sms.url');
        $this->user = config('services.sms.user');
        $this->password = config('services.sms.password');
    }

    public function send(): array
    {
        if (!$this->baseUrl || !$this->user || !$this->password) {
            return ['sent' => false, 'error' => 'POCT_GOYERCHINI_URL / USER / PASS is not configured'];
        }

        try {
            $response = Http::timeout(15)->get( $this->baseUrl, [
                'user'      => $this->user,
                'password'  => $this->password,
                'gsm'       => $this->to,
                'text'      => $this->message
            ])->body();
        } catch (\Throwable $e) {
            return ['sent' => false, 'error' => $e->getMessage()];
        }

        $responseLines = explode('&', trim($response));

        $data = ['raw' => $response];

        foreach ($responseLines as $responseLine)
        {
            $segments = explode('=', $responseLine, 2);

            $value = $segments[1] ?? '';

            $data[$segments[0]] = $value != "" ? (is_numeric($value) ? (int) $value : $value) : null;
        }

        $data['sent'] = array_key_exists('errno', $data) && !$data['errno'];

        return $data;
    }
}