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


            'Elchin.tagiyev85@gmail.com',
            'aykhan.gulmammadov@gmail.com',
            'r.rahimov@mobilbroker.az',
            'ZUTECH030@gmail.com',
            'orxan93_quliyev@mail.ru',
            'Shahin.Gasimov@socardalgidj.az',
            'nesirnemetov@gmail.com',
            'akhalilli@proxima.az',
            'humaartroom@gmail.com',
            'eliyevrafael70@gmail.com',
            'mamta@bk.ru',
            's.novruzi@gmail.com',
            'sham-a@mail.ru',
            'mubariz.memmedov1990@gmail.com',
            'krzayeva@aris.az',
            'ilkin.khalili@gilan-knauf.az',
            'benenyarly.R@grb.kz',
            'Xazarauto@gmail.com',
            'office@mbgro.az',
            'xbabayev4466@gmail.com',
            'elnur.novruz@rotantefood.com',
            'allahverdiyeva.vd@azermash.az',
            'mammadov.f@officepro.az',
            'ramil8545@mail.ru',
            'ceferli1986@mail.ru',
            'e.haciyev95@mail.ru',
            'huseyin_memmedov91@bk.ru',
            'elnursa000@gmail.com',
            'info@yesilsaglik.az',
            'kanan@overmetal.az',
            'rbabayev13@gmail.com',
            'Agababa.dadashov@gmail.com',
            'faxri.huseyn@gmail.com',
            'info@parrotias.com',
            'info@tuib.az',
            'İnfo@ahik.org',
            'hresidov@yahoo.com',
            'nargiz.hajiyeva@noytech.com',
            'qafarzade2014@gmail.com',


        ];

        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}