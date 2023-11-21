<?php

namespace App\Http\Controllers;

use App\Mail\ClientEmail;
use App\Mail\Info;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
//        $search = '@';
//        $clients = Client::whereNotNull('email1')->get();
//        foreach ($clients as $client) {
//            $receiverEmailAddress[] = $client->getAttribute('email1');
//        }
        $mailAddress = 'noreply@mobilgroup.az';
        $template = 'email';
        $emails = [



            'ruslanfarajov@uygunlar.com',
            'royal.z@astexnika.com',
            'rasimkerimli@mail.ru',
            'adigozeltiak@gmail.com',
            'info@baroquestyle.az',
            'm.bagirov@bakusteel.com',
            'samir.farzaliyev@gilan-knauf.az',
            'murad_abdullayev_91@refmotors.az',
            'procurement@caspianindustry.com',
            'denizzhasanova@gmail.com',
            'ft.918@mail.ru',
            'ayizahmadov@alumin.com.az',
            'e.rahimov@simplex.az',
            'ekoproductsaz@gmail.com',
            'svetlana.80.gr@gmail.com',
            'alizadenatavan@yandex.ru',
            'aydinallahverdiyev719@gmail.com',
        ];


        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }

    public function sendInfo()
    {
        $mailAddress = 'noreply@mobilmanagement.az';
        $template = 'email2';

        $emails = [
//            'faig@brainlab.az',
//            'faikabdullayev@icloud.com',
//            'Farid.bagirov@aviongroup.az',
//            'faxri.huseyn@gmail.com',
//            'f-aydinoglu@mail.ru',
//            'gnl.baku@mail.ru',
//            'habibov.nadir81@gmail.com',
//            'hresidov@yahoo.com',
//            'humaartroom@gmail.com',
//            'huseyin_memmedov91@bk.ru',
//            'i.mubarizoglu@gmail.com',
//            'ilkin.khalili@gilan-knauf.az',
//            'info@aada.az',
//            'İnfo@ahik.org',
//            'info@auto.az',
//            'info@azintech.az',
//            'info@baroquestyle.az',
//            'info@buludtelecom.com',

//            'qafarzade2014@gmail.com',
            'aliveliyev1607@gmail.com',
            'a.valiyev@mobilgroup.az'
        ];

        Mail::to($emails)->send(new Info($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}