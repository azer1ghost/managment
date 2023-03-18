<?php

namespace App\Http\Controllers;

use App\Mail\ClientEmail;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $search = '@';
        $clients = Client::whereNotNull('email1')->get();
        foreach ($clients as $client) {
            $receiverEmailAddress[] = $client->getAttribute('email1');
        }
        $mailAddress = 'noreply@mobilgroup.az';
        $template = 'email';

        $emails = [
            'agalandarov@bna.az',
            'narginltd@gmail.com',
            'sales@asmetal.az',
            'a.zeynalov@166.az',
            'rajab.feyzullayev@toyota-absheron.az',
            'rajab.feyzullayev@groupmotors.az',
            'accounts@citco.az',
            'a.bahruz@hayatgroup.az',
            'office@fsca.az',
            'ruslanfarajov@uygunlar.com',
            'director@chery.az',
            'ruslanmillennium@gmail.com',
            'b.aliyev@mobilbroker.az',
            'info@mobex.az',
            'royal.z@astexnika.com',
            'qafarzade2014@gmail.com',

        ];

        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}