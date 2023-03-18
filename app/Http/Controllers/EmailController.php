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
            'İnfo@ahik.org',
            'hresidov@yahoo.com',
            'nargiz.hajiyeva@noytech.com',
            'customer1400@gmail.com',
            'office@richsport.az',
            'faikabdullayev@icloud.com',
            'info@jetour.az',
            'rekber@inbox.ru',
            'n.karimli@mobilbroker.az',
            'info@synergia-pharma.com',
            'procurement02@fsca.az',
            'info@mycar.az',
            'axundov.76@mail.ru',
            'office@sakur.az',
            'info@skyzonebaku.com',
            'info@ecobox.az',
            'nazarevich-e@mail.ru',
            'nadir.novruzov@gmail.com',
            'emin_28@mail.ru',
            'habibov.nadir81@gmail.com',
            'konstromat@sement.az',
            'kamilaliyev585@gmail.com',
            'qafarzade2014@gmail.com',
        ];

        Mail::to($emails)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}