<?php

namespace App\Notifications;

use Exception;

class SmsMessage
{
    protected string $to;
    protected array  $lines;
    protected bool $dryrun = false;
    protected string $smsProvider;

    public function __construct(array $lines = [])
    {
        $this->lines = $lines;

        $this->smsProvider = config('broadcasting.smsProvider');
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

    protected function smsProvider($to, $message)
    {
        return (new $this->smsProvider($to, $message))->send();
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        $this->validate();

        if (!$this->checkDryRun()) {

            $response = $this->smsProvider($this->to, implode('. ', $this->lines));

            $this->logRealResponse($response);

            return $response['sent'];
        }

        return true;
    }

    protected function logRealResponse($data)
    {
        \Log::channel('daily')->notice(
            '#SMS Message sended live mode via '. $this->smsProvider .
            '. Sms to: "'. $this->to.
            '". Sms content: "'.implode('. ', $this->lines).
            '". Recieved response - '. json_encode($data)
        );
    }

    /**
     * @throws Exception
     */
    protected function validate()
    {
        if (!$this->to || !count($this->lines)) {
            throw new Exception('SMS not correct.');
        }
    }

    protected function checkDryRun(): bool
    {
        if ($this->dryrun){

            \Log::channel('daily')->notice('#SMS Message sended DryRun mode via '. $this->smsProvider .'. Sms content: '.implode('. ', $this->lines));

            return true;
        }

        return false;
    }

    public function dryRun($dry = true): self
    {
        $this->dryrun = $dry;

        return $this;
    }
}