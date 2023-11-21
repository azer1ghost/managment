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


//            'a.bahruz@hayatgroup.az',
//            'a.nurlan@agsuaqropark.az',
//            'a.zeynalov@166.az',
//            'accounting@comitec-caspian.com',
//            'accounting@mgtransport.az',
//            'accounts@citco.az',
//            'adelya.shabaladova@mail.ru',
//            'adigozeltiak@gmail.com',
//            'afi.hasanov@list.ru',
//            'afqan@iticket.az',
//            'Agababa.dadashov@gmail.com',
//            'agalandarov@bna.az',
//            'akhalilli@proxima.az',
//            'alizada@mesopharm.ru',
//            'alizadenatavan@yandex.ru',
            'allahverdiyeva.vd@azermash.az',
            'almaz.novruzova@chevrolet-auto.az',
            'amil.huseynov@finanskom.az',
            'anaraylant@gmail.com',
            'aquaestetica.az@gmail.com',
            'asifalizadeh88@gmail.com',
            'axundov.76@mail.ru',
            'aydinallahverdiyev719@gmail.com',
            'aygun.ilchin.i@gmail.com',
            'Ayhan.Dumen@ilkconstruction.com',
            'ayizahmadov@alumin.com.az',
            'aykhan.gulmammadov@gmail.com',
            'aytac.ceferova@trion.az',
            'azavto@mail.ru',
            'bakhtizina@gmail.com',
            'baku.rifah@bk.ru',
            'bayramdamirchioglu@gmail.com',
            'benenyarly.R@grb.kz',
            'cavidlatifov@gmail.com',
//            'ceferli1986@mail.ru',
//            'ceo@gtexnology.com',
//            'denizzhasanova@gmail.com',
//            'director@chery.az',
//            'dkardava@c-team.ge',
//            'dovlet@mediland.az',
//            'e.haciyev95@mail.ru',
//            'e.rahimov@simplex.az',
//            'ekoproductsaz@gmail.com',
//            'Elchin.tagiyev85@gmail.com',
//            'eliyevrafael70@gmail.com',
//            'elman@osys.az',
//            'elnur.novruz@rotantefood.com',
//            'elnursa000@gmail.com',
//            'elshadrm@gmail.com',
//            'elvinmisirxanlı@gmail.com',
//            'emin@fleetstock.info',
//            'emin_28@mail.ru',
//            'esmer@a-classs.com',
//            'f.abuzeroglu@mail.ru',
//            'faig@brainlab.az',
//            'faikabdullayev@icloud.com',
//            'Farid.bagirov@aviongroup.az',
//            'farzalli@yandex.com',
//            'faxri.huseyn@gmail.com',
//            'f-aydinoglu@mail.ru',
//            'Fitnessshopaz@gmail.com',
//            'ft.918@mail.ru',
//            'gulnar.akhundova@mammoet.com',
//            'gunay.m@166.az',
//            'gunelgazizadeh@regal.az',
//            'hl.logistic@gmail.com',
//            'hojjat.bfgroup@gmail.com',
//            'hresidov@yahoo.com',
//            'humaartroom@gmail.com',
//            'eliyevrafael70@gmail.com',
//            'ramal_ahmedov@list.ru',
//            'mamta@bk.ru',
//            's.novruzi@gmail.com',
//            'rasul.nagiyev.2015@mail.ru',
//            'sham-a@mail.ru',
//            'krzayeva@aris.az',
//            'ilkin.khalili@gilan-knauf.az',
//            'benenyarly.R@grb.kz',
//            'xbabayev4466@gmail.com',
//            'elnur.novruz@rotantefood.com',
//            'allahverdiyeva.vd@azermash.az',
//            'mammadov.f@officepro.az',
//            'ramal_ahmedov@list.ru',
//            'ceferli1986@mail.ru',
//            'e.haciyev95@mail.ru',
//            'huseyin_memmedov91@bk.ru',
//            'elnursa000@gmail.com',
//            'info@yesilsaglik.az',
//            'kanan@overmetal.az',
//            'rbabayev13@gmail.com',
//            'Agababa.dadashov@gmail.com',
//            'faxri.huseyn@gmail.com',
//            'ramal_ahmedov@list.ru',
//            'info@parrotias.com',
            'aliveliyev016@gmail.com'
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