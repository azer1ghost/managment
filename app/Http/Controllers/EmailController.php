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

            'procurement@tuna.az',
            'emin@fleetstock.info',
            'tunar20082008@gmail.com',
            'y.seferov82@gmail.com',
            'muradagro@yandex.ru',
            'faig@brainlab.az',
            'info@buludtelecom.com',
            'esmer@a-classs.com',
            'Ruslan.Sultanov@greentech.az',
            'namiq.novruznt@gmail.com',
            'kadymov.emin@yandex.com',
            'rasul.nagiyev.2015@mail.ru',
            'azavto@mail.ru',
            'info@integras.az',
            'yaqub022@gmail.com',
            'Khalilova.Masma@bcg.com',
            'reservation@aznur.az',
            'zaka.gubadov@gmail.com',
            'i.mubarizoglu@gmail.com',
            'ramal_ahmedov@list.ru',
            'togrul.ismayil1@gmail.com',
            'm.mahsulov@hotmail.com',
            'r.a557@mail.ru',
            'ceo@gtexnology.com',
            'shakoturqay@gmail.com',
            'mirzalievaaisun@gmail.com',
            'bakhtizina@gmail.com',
            'qafarzade2014@gmail.com',


        ];

        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}