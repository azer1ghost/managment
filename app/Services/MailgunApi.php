<?php

namespace App\Services;

use Mailgun\Mailgun;

class MailgunApi
{
    private string $apiKey, $endpoint, $domain,
        $from = 'noreply@mail.mobilmanagement.com',
        $subject = 'Subject', $text = 'Text',
        $receivers = 'elvin.aqalarov2@gmail.com';

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

    public function from(string $from): MailgunApi
    {
        $this->from = $from;
        return $this;
    }

    public function receivers(string $receivers): MailgunApi
    {
        $this->receivers = $receivers;
        return $this;
    }

    public function subject(string $subject): MailgunApi
    {
        $this->subject = $subject;
        return $this;
    }

    public function text(string $text): MailgunApi
    {
        $this->text = $text;
        return $this;
    }

    public function dryRun(): MailgunApi
    {
        $this->domain = 'sandboxe1a6a9a1bc79469289f37742d4ec27a2.mailgun.org';
        $this->endpoint = 'https://api.mailgun.net';
        $this->from = 'postmaster@sandboxe1a6a9a1bc79469289f37742d4ec27a2.mailgun.org';
        return $this;
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