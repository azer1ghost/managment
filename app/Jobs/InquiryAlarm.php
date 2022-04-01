<?php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Models\Log;
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
        $this->creator = $inquiry->getRelationValue('user');
        $this->title = trans('translates.inquiries.alarm');
        $this->body = $inquiry->getRelationValue('client')->getAttribute('name').' : '.$inquiry->getRelationValue('client')->getAttribute('phone');
        $this->receivers[] = $this->creator;
    }

    public function handle()
    {
        $inquiries = Inquiry::query()->where('notified', 0)->whereNotNull('alarm')->get();

        foreach ($inquiries as $inquiry) {
            if ($inquiry->getAttribute('alarm')->format('Y-m-d h') == now()->format('Y-m-d h')) {
                (new FirebaseApi)->sendNotification($this->creator, $this->receivers, $this->title, $this->body, $this->url);
                (new FirebaseApi)->sendPushNotification($this->receivers, $this->url, $this->title, $this->body);
                $inquiry->setAttribute('notified', 1);
                $inquiry->save();
            }
        }
    }
}
