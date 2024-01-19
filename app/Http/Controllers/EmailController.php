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
            'aliveliyev016@gmail.com',
            'g.aliyeva@mobilgroup.az',
            'z.mustafayeva@mobilgroup.az',
            'j.gojayev@mobilgroup.az',
            'zeyneb.mustafayeva23@gmail.com',


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
//            'aliveliyev016@gmail.com',
//            'javid.affandi@gmail.com'
        ];

        Mail::to($emails)->send(new Info($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}