<?php

namespace App\SmsProviders;

use Exception;
use Illuminate\Support\Facades\Http;

class PoctGoyercini
{
    protected string $user;
    protected string $password;
    protected string $to;
    protected string $message;
    protected array  $lines;
    protected bool $dryrun = false;
    private string $baseUrl;

    public function __construct(array $lines = [])
    {
        $this->lines = $lines;

        $this->baseUrl = config('services.sms.url');
        $this->user = config('services.sms.user');
        $this->password = config('services.sms.password');
    }

    public function line($line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if ($this->dryrun){

            \Log::info('#SMS sms sended DryRun mode');

            return true;
        }

        if (!$this->to || !count($this->lines)) {
            throw new Exception('SMS not correct.');
        }

        $response = Http::get(  $this->baseUrl, [
            'user' => $this->user,
            'password' => $this->password,
            'gsm' => $this->to,
            'text' => implode('. ', $this->lines)
        ])->body();

        $responseLines = explode('&', $response);

        $data = [];

        foreach ($responseLines as $responseLine)
        {
            $segments = explode('=', $responseLine);

            $data[$segments[0]] = $segments[1] != "" ? is_numeric($segments[1]) ? (int) $segments[1] : $segments[1] : null;
        }

        \Log::info('#SMS sms sended live mode. Recieved response - '. $response);

        return !$data['errno'];
    }

    public function dryRun($dry = true): self
    {
        $this->dryrun = $dry;

        return $this;
    }
}