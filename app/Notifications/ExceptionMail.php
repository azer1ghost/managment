<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Throwable;

class ExceptionMail extends Notification implements ShouldQueue
{
    use Queueable;

    public string $content;
    public array $userData = [];

    public function __construct(Throwable $content)
    {
        $this->content = $content;
        $this->userData['fullname'] = auth()->check() ? auth()->user()->getAttribute('fullname') . ' (#' . auth()->id() . ')' : 'Guest';
        $this->userData['ip'] = request()->ip();
        $this->userData['device'] = request()->header('User-Agent');
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
                    ->line(new HtmlString('<strong>User: </strong>' . $this->userData['fullname']))
                    ->line(new HtmlString('<strong>IP: </strong>' . $this->userData['ip']))
                    ->line(new HtmlString('<strong>Device: </strong>' . $this->userData['device']))
                    ->line(new HtmlString('<strong>Error Traces: </strong></br><pre>' . print_r(collect($this->content->getTrace())->pluck('class')->toArray(), true) . '</pre>'));
    }
}
