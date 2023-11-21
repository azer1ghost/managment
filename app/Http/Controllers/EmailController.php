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



            'office@kia.az',
            'agalandarov@bna.az',
            'narginltd@gmail.com',
            'sales@asmetal.az',
            'a.zeynalov@166.az',
            'info@apex.az',
            'rajab.feyzullayev@groupmotors.az',
            'accounts@citco.az',
            'a.bahruz@hayatgroup.az',
            'office@fsca.az',
            'ruslanfarajov@uygunlar.com',
            'director@chery.az',
            'ruslanmillennium@gmail.com',
            'info@baroquestyle.az',
            'info@buludtelecom.com',
            'aliveliyev016@gmail.com',
            'z.mustafayeva@mobilgroup.az',
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