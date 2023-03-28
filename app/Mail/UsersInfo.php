<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsersInfo extends Mailable
{
    use Queueable, SerializesModels;

    protected $sender;
    protected $template;

    public function __construct($sender, $template)
    {
        $this->sender = $sender;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender)->view($this->template);
    }
}
