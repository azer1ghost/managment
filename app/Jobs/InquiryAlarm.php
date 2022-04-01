<?php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Services\FirebaseApi;
use Illuminate\{Bus\Queueable,
    Contracts\Queue\ShouldQueue,
    Foundation\Bus\Dispatchable,
    Queue\InteractsWithQueue,
    Queue\SerializesModels};
use Illuminate\Support\Facades\Log;

class InquiryAlarm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $title;

    public function __construct()
    {
        $this->title = trans('translates.inquiries.alarm');
    }

    public function handle(): void
    {
        $inquiries = Inquiry::query()->where('notified', 0)->whereNotNull('alarm')->get();

        foreach ($inquiries as $inquiry) {
            if ($inquiry->getAttribute('alarm')->format('d m Y H:i') <= now()->format('d m Y H:i')) {
                $url = route('inquiry.show', $inquiry);
                $creator = $inquiry->getRelationValue('user');
                $body = $inquiry->getRelationValue('client')->getAttribute('name').' : '.$inquiry->getRelationValue('client')->getAttribute('phone');

                (new FirebaseApi)->sendNotification($creator, [$creator], $this->title, $body, $url);
                (new FirebaseApi)->sendPushNotification([$creator], $url, $this->title, $body);

                $inquiry->setAttribute('notified', 1);
                $inquiry->save();
            }
        }
    }
}
