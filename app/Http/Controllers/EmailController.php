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

        Mail::to($receiverEmailAddress)->send(new ClientEmail($mailAddress, $template));

        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}