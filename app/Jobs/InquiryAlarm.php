<?php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Models\User;
use App\Services\FirebaseApi;
use Illuminate\{Bus\Queueable,
    Contracts\Queue\ShouldQueue,
    Foundation\Bus\Dispatchable,
    Queue\InteractsWithQueue,
    Queue\SerializesModels};

class InquiryAlarm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(Inquiry $inquiry)
    {
        $this->url = route('inquiry.show', $inquiry);
        $this->creator = $inquiry->getAttribute('user_id');
        $this->title = trans('translates.inquiries.alarm');
        $this->body = $inquiry->getRelationValue('client')->getAttribute('name').' : '.$inquiry->getRelationValue('client')->getAttribute('phone');
        $this->receivers[] = $this->creator;
    }

    public function handle(Inquiry $inquiry)
    {
        $notification_dates = $inquiry->where('notified', 0)->get('alarm');

        foreach ($notification_dates as $date) {
            $alarm = $date->getAttribute('alarm');
        }

        if ($alarm == now()) {
            (new FirebaseApi)->sendNotification($this->creator, $this->receivers, $this->title, $this->body, $this->url);
            $inquiry->setAttribute('notified', 1);
        }
    }
}
