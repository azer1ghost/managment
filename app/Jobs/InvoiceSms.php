<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\Work;
use App\Notifications\NotifyClientAccountantSms;
use App\Notifications\NotifyClientDirectorSms;
use Illuminate\{Bus\Queueable,
    Contracts\Queue\ShouldQueue,
    Foundation\Bus\Dispatchable,
    Queue\InteractsWithQueue,
    Queue\SerializesModels};
use Carbon\Carbon;

class InvoiceSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $message;

    public function __construct()
    {
        $this->message = 'Pulu ödə';
    }

    public function handle(): void
    {
        $works = Work::query()
            ->where('created_at', '>=', Carbon::createFromDate(2023, 5, 1))
            ->whereNotNull('code')
            ->whereNull('paid_at')
            ->whereDate('invoiced_date', '>=', Carbon::now()->subDays(5)->toDateString())
            ->get();
        foreach ($works as $work) {
            $client = Client::where('id', $work->client_id)->first();
            $message = $work->code; // Kodu mesaj olarak kullanın
            (new NotifyClientAccountantSms($message))->toSms($client)->send();
        }

    }
}
