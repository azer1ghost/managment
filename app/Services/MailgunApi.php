<?php

namespace App\Services;

use Mailgun\Mailgun;

class MailgunApi
{
    private string $apiKey, $endpoint, $domain,
        $from = 'noreply@mail.mobilmanagement.com',
        $subject = 'Subject', $text = 'Text';
    private array $receivers = ['elvin.aqalarov2@gmail.com'];

    public function __construct(){
        $this->apiKey = config('services.mailgun.secret');
        $this->endpoint = 'https://' . config('services.mailgun.endpoint');
        $this->domain = config('services.mailgun.domain');
    }

    public function domain(string $domain): MailgunApi
    {
        $this->domain = $domain;
        return $this;
    }

    public function from(string $from)
    {
        $this->from = $from;
    }

    public function receivers(array $receivers)
    {
        $this->receivers = $receivers;
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;
    }

    public function text(string $text)
    {
        $this->text = $text;
    }

    public function send()
    {
        $mg = Mailgun::create($this->apiKey, $this->endpoint);

        $mg->messages()->send($this->domain, [
            'from'    => $this->from,
            'to'      => $this->receivers,
            'subject' => $this->subject,
            'text'    => $this->text
        ]);
    }

}