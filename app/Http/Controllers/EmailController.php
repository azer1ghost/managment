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
//            'Neqliyyat@mail.ru',
//            'info@integras.az',
//            'akhalilli@proxima.az',
//            'office@avtoleasing.az',
//            'ruslanfarajov@uygunlar.com',
//            'zahidismayilov954@gmail.com',
//            'habibov.nadir81@gmail.com',
//            'mubariz.memmedov1990@gmail.com',
//            'ramil8545@mail.ru',
//            'ceferli1986@mail.ru',
//            'e.haciyev95@mail.ru',
//            'y.seferov82@gmail.com',
//            'r.a557@mail.ru',
//            'GASANOVSABUHI@MAIL.RU',
//            'a.bahruz@hayatgroup.az',
//            'n.karimli@mobilbroker.az',
//            'r.rahimov@mobilbroker.az',
//            'faxri.huseyn@gmail.com',
//            'ramal_ahmedov@list.ru',
//            'info@mobilbroker.az',
//            'hresidov@yahoo.com',
//            'usadiqzade@rsl.az',
//            'tunar20082008@gmail.com',
//            'rasul.nagiyev.2015@mail.ru',
//            'zaka.gubadov@gmail.com',
//            'mahmud@demiragac.net',
//            'leaoazerbaijan@yahoo.com',
//            'elshadrm@gmail.com',
//            'mamed_dash@hotmail.com',
//            'rufat.bayramov@licon.az',
//            'gunay.m@166.az',
//            'İnsafkarimovaze@gmail.com',
//            'yusiflişemsiyye@gmail.com',
//            'nicat-e@mail.ru',
//            'railway.099@gmail.com',
//            'office@euro-asialogistic.com',
//            'maqa089@bk.ru',
//            'eldanizsattarov@gmail.com',
//            'mustafa.toptas@mps.com.tr',
//            'ilkin.khudaverdiyev@lexusbaku.az',
//            'ibrahim@energazer.com',
//            '1@mail.ru',
//            'velizadeehtiram@gmail.com',
//            '1@mail.ru',
//            'info@texnolab.az',
//            'hashimovelnar@gmail.com',
//            'fx12@mail.ru',
//            'Mushfig.Mukhtarov@isr-industries.az',
//            'procurement@mastertools-az.com',
//            'huseyn.hasanov@startgs.az',
//            'rauf.teyyubov@shinkar.az',
//            'rufatjarchiyev@gmail.com',
//            'arif@azmetkim.az',
//            '1@mail.ru',
//            '1@mail.ru',
//            'hojjat.bfgroup@gmail.com',
//            'emin@fleetstock.info',
//            'muradagro@yandex.ru',
//            'faig@brainlab.az',
//            'info@buludtelecom.com',
//            'ruslan.Sultanov@greentech.az',
//            'kadymov.emin@yandex.com',
//            'shakoturqay@gmail.com',
//            'Y.seferov82@gmail.com',
//            'azavto@mail.ru',
//            'Khalilova.Masma@bcg.com',
//            '1@mail.ru',
//            'reservation@aznur.az',
//            'i.mubarizoglu@gmail.com',
//            'ceo@gtexnology.com',
            'shakoturqay@gmail.com',
            'mirzalievaaisun@gmail.com',
            'bakhtizina@gmail.com',
            'gnl.baku@mail.ru',
            'info@jetour.az',
            'y.seferov82@gmail.com',
            'info@synergia-pharma.com',
            'info@mycar.az',
            'office@sakur.az',
            'info@skyzonebaku.com',
            'info@ecobox.az',
            'nazarevich-e@mail.ru',
            'nadir.novruzov@gmail.com',
            'anaraylant@gmail.com',
            'Farid.bagirov@aviongroup.az',
            'sq.konsaltinq@mail.ru',
            'procurement@caspianindustry.com',
            'royal.z@astexnika.com',
            'info@auto.az',
            'necefov.2000@gmail.com',
//            'f.abuzeroglu@mail.ru',
//            'ZUTECH030@gmail.com',
//            'Shahin.Gasimov@socardalgidj.az',
//            'humaartroom@gmail.com',
//            'akhalilli@proxima.az',
//            'eliyevrafael70@gmail.com',
//            'mamta@bk.ru',
//            'ramal_ahmedov@list.ru',
//            '1@mail.ru',
//            'y.seferov82@gmail.com',
//            'info@jetour.az',
//            'sham-a@mail.ru',
//            'krzayeva@aris.az',
//            'ilkin.khalili@gilan-knauf.az',
//            'benenyarly.R@grb.kz',
//            'office@mbgro.az',
//            'xbabayev4466@gmail.com',
//            'elnur.novruz@rotantefood.com',
//            'almaz.novruzova@chevrolet-auto.az',
//            'allahverdiyeva.vd@azermash.az',
//            'mammadov.f@officepro.az',
//            'ramal_ahmedov@list.ru',
//            'huseyin_memmedov91@bk.ru',
//            'info@yesilsaglik.az',
//            'yaqub022@gmail.com',
//            'rbabayev13@gmail.com',
//            'Agababa.dadashov@gmail.com',
//            'faxri.huseyn@gmail.com',
//            'info@parrotias.com',
//            'info@tuib.az',
//            'İnfo@ahik.org',
//            'nargiz.hajiyeva@noytech.com',
//            'info@aada.az',
//            'narginltd@gmail.com',
//            's.emin@technologies.az',
//            'bayramdamirchioglu@gmail.com',
//            'ramin.a.2014@mail.ru',
//            'cavidlatifov@gmail.com',
//            'niyaziyusifov55@gmail.com',
//            'rufat.seyidov@tezlogistics.az',
//            'niyaziyusifov55@gmail.com',
//            'info@genparts.az',
//            'gunelgazizadeh@regal.az',
//            'ZUTECH030@gmail.com',
//            'm.gadiyeva@avg.az',
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