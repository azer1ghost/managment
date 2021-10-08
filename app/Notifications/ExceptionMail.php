<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ExceptionMail extends Notification
{
    use Queueable;

    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Error report from ' . config('app.name'))
                    ->line(new HtmlString('<strong>Message: </strong>' . $this->content->getMessage()))
                    ->line(new HtmlString('<strong>Error file: </strong>' . $this->content->getFile()))
                    ->line(new HtmlString('<strong>Error line: </strong>' . $this->content->getLine()))
                    ->line(new HtmlString('<strong>Error Traces: </strong></br><pre>' . print_r(collect($this->content->getTrace())->pluck('class')->toArray(), true) . '</pre>'));
    }
}
