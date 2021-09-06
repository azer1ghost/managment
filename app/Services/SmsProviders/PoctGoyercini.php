<?php

namespace App\Services\SmsProviders;

use Illuminate\Support\Facades\Http;

class PoctGoyercini
{
    protected string $user;
    protected string $password;
    protected ?string $to;
    protected string $message;
    private string $baseUrl;

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
        $response = Http::get( $this->baseUrl, [
            'user'      => $this->user,
            'password'  => $this->password,
            'gsm'       => $this->to,
            'text'      => $this->message
        ])->body();

        $responseLines = explode('&', $response);

        $data = [];

        foreach ($responseLines as $responseLine)
        {
            $segments = explode('=', $responseLine);

            $data[$segments[0]] = $segments[1] != "" ? is_numeric($segments[1]) ? (int) $segments[1] : $segments[1] : null;
        }

        $data['sent'] = !$data['errno'];

        return $data;
    }
}