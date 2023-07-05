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



    public function handle(): void
    {
        $works = Work::query()
            ->where('created_at', '>=', Carbon::createFromDate(2023, 5, 1))
            ->whereNotNull('code')
            ->whereNull('paid_at')
            ->whereDate('invoiced_date', '>=', Carbon::now()->subDays(5)->toDateString())
            ->get();

        $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', 'Ə', 'ə');
        $replace = array('C', 'c', 'G', 'g', 'i', 'I', 'O', 'o', 'S', 's', 'U', 'u', 'E', 'e');

        foreach ($works as $work) {
            $client = Client::where('id', $work->client_id)->first();
            $clientText = trim($client->getAttribute('fullname'));
            $clientName = str_replace($search, $replace, $clientText);
            $message = 'Deyerli ' . $clientName. '.' .   $work->getAttribute('code') . '№-li elektron qaimenize esasen odenis etmeyiniz xahis olunur';
            (new NotifyClientAccountantSms($message))->toSms($client)->send();
        }

    }
}
