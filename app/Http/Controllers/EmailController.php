<?php

namespace App\Http\Controllers;

use App\Mail\ClientEmail;
use App\Mail\UsersInfo;
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


        Mail::to($receiverEmailAddress)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }

    public function sendInfo()
    {
        $mailAddress = 'noreply@mobilmanagement.az';
        $template = 'email';

        $emails = [
            'a.ismikhanov@mobilbroker.az',
            'u.hasanova@mobilbroker.az',
            's.allahverdiyev@mobilbroker.az',
            'x.huseynova@mobilbroker.az',
            'n.mirzeyev@mobilbroker.az',
            'g.hasanova@mobilbroker.az',
            'nazifa.m@mobilbroker.az',
            'a.allahverdiyev@mobilbroker.az',
            'i.talibov@mobilbroker.az',
            'b.aliyev@mobilbroker.az',
            's.rzayev@mobilbroker.az',
            'g.mehdizada@mobilbroker.az',
            's.qarayeva@mobilbroker.az',
        ];
//        'nigar.i@mobilbroker.az',
//            'n.novruzov@mobilbroker.az',
//            'n.karimli@mobilbroker.az',
//            'p.ahmedov@mobilbroker.az',
//            'a.rzayev@mobilbroker.az',
//            'g.mammadova@mobilbroker.az',
//            'f.nazarli@mobilbroker.az',
//            'sh.babayeva@mobilbroker.az',
//            'r.rahimov@mobilbroker.az',
//            'r.farajli@mobilbroker.az',
//            'm.hasanov@mobilbroker.az',
//            'f.nazarov@mobilbroker.az',
//            'n.azimov@mobilbroker.az',
//            'r.seyidov@mobilgroup.az',
//            'z.namazli@mobilgroup.az',
//            'c.allahverdiyev@mobilbroker.az',
        Mail::to($emails)->send(new UsersInfo($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}