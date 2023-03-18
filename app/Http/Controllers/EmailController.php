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

            'contabilita.baku@esteri.it',
            'info@baroquestyle.az',
            'm.bagirov@bakusteel.com',
            'samir.farzaliyev@gilan-knauf.az',
            'murad_abdullayev_91@refmotors.az',
            'denizzhasanova@gmail.com',
            'gnl.baku@mail.ru',
            'a.nurlan@agsuaqropark.az',
            'e.rahimov@simplex.az',
            'ekoproductsaz@gmail.com',
            'alizadenatavan@yandex.ru',
            'svetlana.80.gr@gmail.com',
            'aydinallahverdiyev719@gmail.com',
            'almaz.novruzova@chevrolet-auto.az',
            'f-aydinoglu@mail.ru',
            'info@faridoptic.az',
            'saleh@azmetkim.az',
            'info@ozsut.az',
            'oksana.osmanova@aemgroup.az',
            'info@azintech.az',
            'orkhan_a@icloud.com',
            'afi.hasanov@list.ru',
            's.emin@technologies.az',
            'aygun.ilchin.i@gmail.com',
            'nabi.ismailov@ferrari-baku.com',
            'mahmud@demiragac.net',
            'qafarzade2014@gmail.com',

        ];

        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}