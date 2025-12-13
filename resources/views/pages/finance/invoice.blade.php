@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))
@php
    // Check if invoice is saved (has ID) - determines if it's immutable
    $hasId = $data->getAttribute('id') !== null;
    
    // Check if invoice is a draft/duplicate (should be editable):
    // 1. Created within last hour by current user (likely a duplicate)
    // 2. OR invoice number contains "-COPY" (legacy check)
    $isDraft = false;
    if ($hasId) {
        $createdAt = $data->getAttribute('created_at');
        $createdBy = $data->getAttribute('created_by');
        $invoiceNo = $data->getAttribute('invoiceNo');
        
        // Check if recently created (within last hour) by current user
        if ($createdAt && $createdBy == auth()->id()) {
            $hoursSinceCreation = now()->diffInHours($createdAt);
            if ($hoursSinceCreation < 1) {
                $isDraft = true;
            }
        }
        
        // Legacy check: if invoice number contains "-COPY", treat as draft
        if (strpos($invoiceNo, '-COPY') !== false) {
            $isDraft = true;
        }
    }
    
    // Invoice is saved (immutable) only if it has ID AND is not a draft
    $isSaved = $hasId && !$isDraft;
    
    $company = $data->getAttribute('company');
    $representer = "Gömrük Təmsilçisi";

 if ($company == 'mbrokerKapital') {
     $companyName = "\"Mobil Broker\" MMC";
     $voen = "1804705371";
     $hh = "AZ78AIIB400500D9447193478229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Vüsal Xəlilov İbrahim oğlu";
     $whoFooter = "V.İ.Xəlilov";
      $stamp = asset('assets/images/finance/mbroker1.jpeg');

 } else if ($company == 'mbrokerRespublika') {
     $companyName = "\"Mobil Broker\" MMC";
     $voen = "1804705371";
     $hh = "AZ17BRES00380394401114863601";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Vüsal Xəlilov İbrahim oğlu";
     $whoFooter = "V.İ.Xəlilov";
      $stamp = asset('assets/images/finance/mbroker1.jpeg');
  }else if ($company == 'mgroupRespublika') {
     $companyName = "\"Mobil Group\" MMC";
     $voen = "1405261701";
     $hh = "AZ31BRES00380394401115941601";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Vüsal Xəlilov İbrahim oğlu";
     $whoFooter = "V.İ.Xəlilov";
     $stamp = asset('assets/images/finance/mgroup1.jpeg');

 } else if ($company == 'garantKapital') {
     $companyName = "\"Garant Broker\" MMC";
     $voen = "1803974481";
     $hh = "AZ56AIIB400500D9447227965229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Əhmədbəy İsmixanov Səfixan oğlu";
     $whoFooter = "Ə.S.İsmixanov";
     $stamp = asset('assets/images/finance/gbroker1.jpeg');

 } else if ($company == 'garantRespublika') {
     $companyName = "\"Garant Broker\" MMC";
     $voen = "1803974481";
     $hh = "AZ95BRES00380394401114875001";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Əhmədbəy İsmixanov Səfixan oğlu";
     $whoFooter = "Ə.S.İsmixanov";
     $stamp = asset('assets/images/finance/gbroker1.jpeg');

 } else if ($company == 'rigelKapital') {
     $companyName = "\"Rigel Group\" MMC";
     $voen = "1805978211";
     $hh = "AZ61AIIB400500E9445911817229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Xəlilova Lamiyə Fərhad qızı";
     $whoFooter = "L.İ.Xəlilova";
     $stamp = asset('assets/images/finance/rigel1.jpeg');

 } else if ($company == 'tgroupKapital') {
     $companyName = "\"Tedora Group\" MMC";
     $voen = "1008142601";
     $hh = "AZ06AIIB400500F9443614259229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Toğrul Surxayzadə Məhərrəm oğlu";
     $whoFooter = "T.M.Surxayzadə";
     $stamp = asset('assets/images/finance/tedora1.jpeg');

 } else if ($company == 'dgroupKapital') {
     $companyName = "\"Declare Group\" MMC";
     $voen = "1406438851";
     $hh = "AZ62AIIB400500F9443405268229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Mahir Həsənquliyev Tahir oğlu";
     $whoFooter = "M.T.Həsənquliyev";
     $stamp = asset('assets/images/finance/declare1.jpeg');

 } else if ($company == 'rigelRespublika') {
     $companyName = "\"Rigel Group\" MMC";
     $voen = "1805978211";
     $hh = "AZ43BRES00380394401162048201";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Xəlilova Lamiyə Fərhad qızı";
     $whoFooter = "L.İ.Xəlilova";
     $stamp = asset('assets/images/finance/rigel1.jpeg');

 } else if ($company == 'mindRespublika') {
     $companyName = "\"Mind Services\" MMC";
     $voen = "1506046601";
     $hh = "AZ88BRES00380394401162079401";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Musayev Ağarza Musarza oğlu";
     $whoFooter = "A.M.Musayev";
     $stamp = asset('assets/images/finance/mind1.jpeg');

} else if ($company == 'mindKapital') {
     $companyName = "\"Mind Services\" MMC";
     $voen = "1506046601";
     $hh = "AZ28AIIB400500E9444984575229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "Kapital Bank ASC KOB mərkəzi filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Musayev Ağarza Musarza oğlu";
     $whoFooter = "A.M.Musayev";
     $stamp = asset('assets/images/finance/mind1.jpeg');

 } else if ($company == 'asazaRespublika') {
     $companyName = "\"ASAZA FLKS\" MMC";
     $voen = "1805091391";
     $hh = "AZ80BRES00380394401196199101";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Sabir Tahirov Zakir oğlu";
     $whoFooter = "S.Z.Tahirov";
     $stamp = asset('assets/images/finance/asaza1.jpeg');

 }
 else if ($company == 'asazaKapital') {
     $companyName = "\"ASAZA FLKS\" MMC";
     $voen = "1805091391";
     $hh = "AZ79AIIB400500E9446021649229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "Kapital Bank ASC KOB mərkəzi filialı";
     $kod = "201412";
     $bvoen = "9900001901";
     $swift = "AIIBAZ2XXXX";
     $who = "Sabir Tahirov Zakir oğlu";
     $whoFooter = "S.Z.Tahirov";
     $stamp = asset('assets/images/finance/asaza1.jpeg');

 }else if ($company == 'mtechnologiesRespublika') {
     $companyName = "\"Mobil Technologies\" MMC";
     $voen = "1804325861";
     $hh = "AZ20BRES00380394401131856201";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Sabir Tahirov Zakir oğlu";
     $whoFooter = "S.Z.Tahirov";
     $stamp = asset('assets/images/finance/mtech1.jpeg');

 } else if ($company == 'mtechnologiesKapital') {
     $companyName = "\"Mobil Technologies\" MMC";
     $voen = "1804325861";
     $hh = "AZ52AIIB400600D9447189871229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "Kapital Bank ASC KOB mərkəzi filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Sabir Tahirov Zakir oğlu";
     $whoFooter = "S.Z.Tahirov";
     $stamp = asset('assets/images/finance/mtech1.jpeg');

 } else if ($company == 'logisticsKapital') {
     $companyName = "\"Mobil Logistics\" MMC";
     $voen = "1804811521";
     $hh = "AZ85AIIB400500D9447161910229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Xəlilova Lamiyə Fərhad qızı";
     $whoFooter = "L.F.Xəlilova";
     $representer = "Ekspeditor";
     $stamp = asset('assets/images/finance/logistics1.jpeg');
 } else if ($company == 'logisticsRespublika') {
     $companyName = "\"Mobil Logistics\" MMC";
     $voen = "1804811521";
     $hh = "AZ77BRES00380394401116001301";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Xəlilova Lamiyə Fərhad qızı";
     $whoFooter = "L.F.Xəlilova";
     $representer = "Ekspeditor";
     $stamp = asset('assets/images/finance/logistics1.jpeg');
 } else if ($company == 'mobexRespublika') {
     $companyName = "\"Mobil Express\" MMC";
     $voen = "1804892041";
     $hh = "AZ55BRES40050AZ0111181435001";
     $mh = "AZ80NABZ01350100000000014944";
     $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
     $kod = "507547";
     $bvoen = "9900001901";
     $swift = "BRESAZ22";
     $who = "Həsənova Vüsalə İbrahim qızı";
     $whoFooter = "V.İ.Həsənova";
     $stamp = asset('assets/images/finance/mobex1.jpeg');
 } else if ($company == 'mobexKapital') {
     $companyName = "\"Mobil Express\" MMC";
     $voen = "1804892041";
     $hh = "AZ64AIIB400500D9447160681229";
     $mh = "AZ37NABZ01350100000000001944";
     $bank = "Kapital Bank ASC KOB mərkəzi filialı";
     $kod = "201412";
     $bvoen = "9900003611";
     $swift = "AIIBAZ2XXXX";
     $who = "Həsənova Vüsalə İbrahim qızı";
     $whoFooter = "V.İ.Həsənova";
     $stamp = asset('assets/images/finance/mobex1.jpeg');
} else {
    // Default values if company doesn't match any condition
    $companyName = "Şirkət";
    $voen = "";
    $hh = "";
    $mh = "";
    $bank = "";
    $kod = "";
    $bvoen = "";
    $swift = "";
    $who = "";
    $whoFooter = "";
    $stamp = asset('assets/images/finance/default-stamp.jpeg');
}
@endphp

@section('style')
    <style>

        p, h1, h2, h3, h4, h5, h6, span, td, th {
            font-weight: bolder;
        }
        .tabelBorder {
            border: solid black 2px !important;
            border-color:black !important;
        }
        @media print {
            .tabelBorder {
                border: solid black 2px !important;

            }
        }

        body {
            background: white; !important;
        }
        .imza {
             background-image: url('{{$stamp ?? asset('assets/images/finance/default-stamp.jpeg')}}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }
        .button-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ccc;
            color: #000;
            cursor: pointer;
            border-radius: 5px;
            user-select: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .button-label:hover {
            background-color: #aaa;
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"]:checked + label {
            background-color: #00aaff;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    @if(auth()->user()->isDeveloper() || auth()->user()->isDirector() || in_array(auth()->user()->id, [103, 17, 120, 216, 120, 185, 188, 212, 213, 216, 173, 33, 32, 118, 86, 232]))
        <div class="text-center">
        <input type="checkbox" id="imzala" @if($data->getAttribute('is_signed') == 1) checked @endif>
        <label for="imzala" class="button-label imzala-label">İmzala</label>

        <input type="checkbox" id="copy">
        <label for="copy" class="button-label copy-label">Nüsxə</label>
    </div>
    @endif
    <button onclick="printCards()" class="btn btn-primary float-right">Print All</button>

    <div class="container">
        <br>
        @if($isSaved)
            <div class="alert alert-info">
                <strong>Məlumat:</strong> Bu qaimə saxlanıldıqdan sonra dəyişdirilə bilməz. Dəyişiklik etmək istəyirsinizsə, lütfən qaiməni kopyalayın və yeni qaimə yaradın.
            </div>
        @else
            <div class="alert alert-warning">
                <strong>Məlumat:</strong> Bu qaimə hələ saxlanmayıb. Bütün dəyişiklikləri etdikdən sonra "Yadda Saxla" düyməsini basın.
            </div>
        @endif
        <button onclick="printCard1()" class="btn btn-primary float-right">Print</button>
        @if(!$isSaved)
            <button onclick="createInvoice()" class="btn btn-success float-right mr-2">Yadda Saxla</button>
        @endif
        <div class="card" id="printCard1">
            <div class="card-body">
                <h2 class="text-center companyName" data-company="{{$company}}" id="companyName">{{$companyName}}</h2>
                <h3 class="text-center">HESAB FAKTURA &numero; <span id="invoiceNo" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('invoiceNo')}}</span></h3>
                <h6 class=" mb-2"><span class="companyName">{{$companyName}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VOEN: <span class="voen">{{$voen}}</span></h6>
                <h6 class=" mb-2">H/H: <span class="hh">{{$hh}}</span></h6>
                <h6 class=" mb-2">M/H <span class="mh">{{$mh}}</span></h6>
                <h6 class=" mb-2">BANK: <span class="bank">{{$bank}}</span> KOD: <span class="kod">{{$kod}}</span></h6>
                <h6 class=" mb-2">BANK VOEN: <span class="bbank">{{$bvoen}}</span> &nbsp;&nbsp;  S.W.I.F.T: <span class="swift">{{$swift}}</span></h6>

                <h1 class="text-center companyName" id="getCompany">{{$companyName}}</h1>
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th colspan="2" rowspan="2"></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2" class="border border-bottom-0 tabelBorder clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</td>
                        <td class="border tabelBorder">Tarix: <span id="invoiceDate" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('invoiceDate')}}</span></td>
                        <td class="border tabelBorder">&numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0 tabelBorder">VÖEN: <span class="clientVoen">{{$data->getRelationValue('financeClients')->getAttribute('voen')}}</span></td>
                        <td class="border tabelBorder">Ödəmə növü: <span id="paymentType" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('paymentType')}}</span></td>
                        <td class="border tabelBorder total"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>

                <table class="table table-borderless tabelBorder">
                    <thead>
                    <tr>
                        <th class="tabelBorder">Əmtəənin Adı</th>
                        <th class="tabelBorder">Ölçü Vahidi</th>
                        <th class="tabelBorder">Miqdarı</th>
                        <th class="tabelBorder">Qiymət</th>
                        <th class="tabelBorder">Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    @foreach(json_decode($data->getAttribute('services')) as $service)
                    <tr>
                        <td class="tabelBorder" @if(!$isSaved) contenteditable="true" @endif>{{$service->input1}}</td>
                        <td class="tabelBorder">Ədəd</td>
                        <td class="tabelBorder count count-{{$loop->iteration}}" @if(!$isSaved) contenteditable="true" @endif data-row="{{$loop->iteration}}">{{$service->input3}}</td>
                        <td class="tabelBorder amount amount-{{$loop->iteration}}" @if(!$isSaved) contenteditable="true" @endif data-row="{{$loop->iteration}}">{{$service->input4}}</td>
                        <td class="tabelBorder overal">{{$service->input3 * $service->input4}}</td>
                        <td class="tabelBorder">
                            @if(!$isSaved)
                                <a onclick="deleteOldRow({{$loop->iteration}})" class="btn btn-danger btn-sm">Sil</a>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                    <tr id="form-area">
                        <td id="loginput"><input type="text" class="form-control" id="input1l"></td>
                        <td id="brokerinput">
                            <select id="input1" class="form-control">
                                <option>Elektron GB-nin tərtib olunması xidməti</option>
                                <option>Elektron Qısa İdxal GB-nin tərtib olunması xidməti</option>
                                <option>CMR-in tərtib olunması xidməti</option>
                                <option>TIRCARNET-in tərtib olunması xidməti</option>
                                <option>Gömrük kodunun müəyyən edilməsi</option>
                                <option>Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin hazırlanması</option>
                                <option>Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin çeşidlənməsi</option>
                                <option>Təmsil etdiyi şəxsin tapşırığı əsasında yüklərin və nəqliyyat vasitələrinin tam gömrük rəsmiləşdirilməsi</option>
                                <option>Təmsil etdiyi şəxsin tapşırığı əsasında nəqliyyat vasitələrinin tam gömrük rəsmiləşdirilməsi(Tranzit qeydiyyat nişanının alınması)</option>
                                <option>Gömrük rüsumlarının əvvəlcədən hesablanması</option>
                                <option>Təmsilçilik xidməti</option>
                                <option>Təmsilçilik (Sertifikatın alınması)</option>
                                <option>Təmsilçilik (Tələb Olunan Sertifikatın alınması)</option>
                                <option>Qismən Təmsilçilik xidməti</option>
                                <option>Printerlərə texniki baxışın göstərilməsi</option>
                                <option>Serverlərə texniki baxışın göstərilməsi</option>
                                <option>Kompüterlərə texniki baxışın göstərilməsi</option>
                                <option>İnvoysun Hazırlanması</option>
                                <option>Məktubların hazırlanması</option>
                                <option>Təmsil etdiyi şəxsin etibarnaməsi əsasında yüklərin gömrük anbarından çıxarılması</option>
                                <option>Təmsil etdiyi şəxsin tapşırığı əsasında nəqliyyat vasitələrinin çıxarılması</option>
                                <option>Gömrük rəsmiləşdirilməsinin həyata keçirilməsi zamanı gömrük məmuru ilə əlaqə yaradılması xidməti</option>
                                <option>Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin yoxlanılması</option>
                                <option>Konsultasiya Xidməti</option>
                                <option>Çəki listi(Packing List) Tərtib olunması</option>
                                <option>Ərazi Xərci</option>
                                <option>Gömrük təmsilçiliyi xidməti</option>
                                <option>Etibarnamənin tərtib olunması</option>
                                <option>Anbar və Terminal ödənişi</option>
                                <option>Elektron Qısa İdxal GB-nin tərtib olunması xidməti (Əsas Vərəq)</option>
                                <option>Elektron Qısa İdxal GB-nin tərtib olunması xidməti (Əlavə Vərəq)</option>
                                <option>Sadələşdirilmiş Bəyannamə</option>
                                <option>Gömrükxana xidməti və Elektron Gömrük Bəyannamələrinin tərtib olunması</option>
                                <option>Subicarə xidməti</option>
                                <option>Yüklərin təhvil-təslim aktı əsasında ünvana çatdırılması xidməti(Bakı şəhəri üzrə)</option>
                                <option>Digər xidmət</option>
                                <option>Elektron GB-nin tərtib olunması(əsas vərəq)</option>
                                <option>Elektron GB-nin tərtib olunması(əlavə vərəq)</option>
                                <option>Ərazi Xərci və Digər Ödənişlər</option>
                                <option>Laboratoriya təmsilçiliyi</option>
                                <option>Texniki xidmətlərin göstərilməsi</option>
                                <option>Təsdiq edici sənəd hazırlanması xidməti</option>
                                <option>Gigiyenik sertifikatın alınması xidməti</option>
                                <option>AQTA sertifikatın alınması üçün müraciət xidməti</option>
                                <option>Mənşə sertifikatı üçün müraciət xidməti</option>
                                <option>Müvəqqəti saxlanc bəyannaməsinin tərtib olunması xidməti</option>
                                <option>Müvəqqəti idxal ərizəsinin yazılması xidməti</option>
                                <option>İnvoys ərizəsinin yazılması xidməti</option>
                                <option>Barkod dəyişdirilməsi ərizəsinin yazılması xidməti</option>
                                <option>Ad dəyişmə ərizəsinin yazılması İdxalatçının adı xidməti</option>
                                <option>Gömrük idarəsinin (təyinat) dəyişdirilməsi ərizəsinin yazılması</option>
                                <option>Öhdəlik ərizəsinin yazılması xidməti</option>
                                <option>Laboratoriya ərizəsinin (şəxsi müraciət əsasında) yazılması xidməti</option>
                                <option>Ümumi düzəliş ərizəsinin yazılması xidməti</option>
                                <option>Gömrük bəyannaməsi, invoys və digər xərclər əsas götürülərək maya dəyərinin dəqiq hesablanması xidməti</option>
                                <option>Təsdiqedici sənəd üçün müraciət</option>
                            </select>
                        </td>
                        <td>Ədəd</td>
                        <td><input type="text" class="form-control" id="input3"></td>
                        <td><input type="text" class="form-control" id="input4"></td>
                        <td id="print-area">
                            @if(!$isSaved)
                                <button onclick="addRow()" class="btn btn-primary">+</button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">CƏMİ</td>
                        <td  class="tabelBorder sum" id="sum"></td>
                    </tr>
                    <tr id="vatColumn">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder vat" id="vat"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder total" id="total"></td>
                    </tr>
                    <tr>
                        <td class="tabelBorder" colspan="5" id="total-text">Yekun Məbləğ: <span class="total">0</span> AZN (<span class="numberWord"></span>)</td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="imzalar" style="width: 350px; height: 350px">
                    <p class="invoiceNumbers" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('invoiceNumbers')}}</p>
                </div>
                <br>

                <p class="float-left">{{$companyName}}<span class="companyName"></span>-nin direktoru</p>
                <p class="float-right" id="who-footer">{{$whoFooter}}</p>
            </div>

        </div>
    </div>
    <div class="container">
        <br>
        <button onclick="printCard2()" class="btn btn-primary float-right">Print</button>
        <div class="card" id="printCard2">
            <div class="card-body">
                <img src="{{asset('assets/images/finance/nusxe1.jpeg')}}" class="copies" width="400" alt="">
                 <p class="float-left">Bakı Şəhəri</p>
          
                 <p class="float-right" id="protocolDate" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('protocolDate')}}</p>
                 <br>
                 <br>
                 <br>
                 <h3 class="mb-2 text-center">QİYMƏT RAZILAŞDIRMA PROTOKOLU &numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></h3>
                 <h6 class="mb-2 text-center"><span class="companyName">{{$companyName}}</span> və <span class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</span> arasında bağlanan <span class="contractDate">{{$data->getAttribute('contractDate')}}</span> tarixli</h6>
                 <h6 class="mb-2 text-center">&numero; <span class="contractNo" id="contractNo" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('contractNo')}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Müqaviləyə əlavə</h6>
                 <br>

                <table class="table table-borderless tabelBorder">
                    <thead>
                    <tr>
                        <th class="tabelBorder">Sıra &numero;</th>
                        <th class="tabelBorder">İş və Xidmətin Adı</th>
                        <th class="tabelBorder">Ölçü Vahidi</th>
                        <th class="tabelBorder">Say</th>
                        <th class="tabelBorder">Vahidin Qiyməti</th>
                        <th class="tabelBorder">Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body2">
                    @foreach(json_decode($data->getAttribute('services')) as $service)
                        <tr>
                            <td class="tabelBorder">{{$loop->iteration}}</td>
                            <td class="tabelBorder">{{$service->input1}}</td>
                            <td class="tabelBorder">Ədəd</td>
                            <td class="tabelBorder" id="countCell2-{{$loop->iteration}}">{{$service->input3}}</td>
                            <td class="tabelBorder" id="amountCell2-{{$loop->iteration}}">{{$service->input4}}</td>
                            <td class="tabelBorder" id="overalCell2-{{$loop->iteration}}">{{$service->input3 * $service->input4}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">CƏMİ</td>
                        <td class="tabelBorder sum"></td>
                    </tr>
                    <tr  id="vatColumn2">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder vat"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder total"></td>
                    </tr>
                    </tbody>
                </table>
                <br>

                    <p class="invoiceNumbers" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('invoiceNumbers')}}</p>

                <br>
                <p>Yekun Məbləğ:  <span class="total">0</span>  AZN (<span class="numberWord"></span>)</p>
                <br><br>
                <div class="float-left text-center">
                    <h6 id="temsilci">{{$representer}}</h6>
                    <br>
                    <p class="companyName">{{$companyName}}</p>
                    <p>VÖEN: <span class="voen">{{$voen}}</span></p>
                    <p>H/H: <span class="hh"></span>{{$hh}}</p>
                    <p>M/H <span class="mh"></span>{{$mh}}</p>
                    <p>KOD: <span class="kod"></span>{{$kod}}</p>
                    <p>BANK: <span class="bank"></span>{{$bank}}</p>
                    <p>BANK VOEN: <span class="bvoen">{{$bvoen}}</span></p>
                    <p>S.W.I.F.T: <span class="swift">{{$swift}}</span></p>
                    <br>
                    <div class="imzalar" style="width: 350px; height: 350px">
                        <p>Direktor: <span id="who">{{$who}}</span></p>
                        <p>İmza, möhür</p>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    </div>
                </div>
                <div class="float-right text-center" style="width: 300px; height: 300px">
                    <h6>Sifarişçi</h6>
                    <br>
                    <p class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</p>
                    <p>VÖEN: <span class="clientVoen">{{$data->getRelationValue('financeClients')->getAttribute('voen')}}</span></p>
                    <p>H/H: <span class="clienthh">{{$data->getRelationValue('financeClients')->getAttribute('hn')}}</span></p>
                    <p>M/H <span class="clientmh">{{$data->getRelationValue('financeClients')->getAttribute('mh')}}</span></p>
                    <p>KOD: <span class="clientCode">{{$data->getRelationValue('financeClients')->getAttribute('code')}}</span></p>
                    <p>BANK: <span class="clientBank">{{$data->getRelationValue('financeClients')->getAttribute('bank')}}</span></p>
                    <p>BANK VOEN: <span class="clientBvoen">{{$data->getRelationValue('financeClients')->getAttribute('bvoen')}}</span></p>
                    <p>S.W.I.F.T: <span class="clientSwift">{{$data->getRelationValue('financeClients')->getAttribute('swift')}}</span></p>
                    <br>
                    <p class="clientWho">{{$data->getRelationValue('financeClients')->getAttribute('orderer')}}</p>
                    <br>
                    <br>
                    <p>İmza, möhür</p>
                    <br><br><br>

                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
            </div>

        </div>
    </div>
    <div class="container">
        <br>
        <button onclick="printCard3()" class="btn btn-primary float-right">Print</button>

        <div class="card" id="printCard3">
            <div class="card-body">
                <img src="{{asset('assets/images/finance/nusxe1.jpeg')}}" class="copies" width="400" alt="">

                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right invoiceDate">{{$data->getAttribute('invoiceDate')}}</p>
                <br>
                <br>
                <br>
                <h6 class=" mb-2 text-center"><span class="companyName">{{$companyName}}</span> və <span class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</span> arasında bağlanan &numero; <span class="contractDate" id="contractDate" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('contractDate')}}</span>  tarixli</h6>
                <h6 class=" mb-2 text-center">müqaviləyə əsasən göstərilən xidmətlərin Təhvil-Təslim aktı &numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></h6>
                <br>
                <table class="table table-borderless tabelBorder">
                    <thead>
                    <tr>
                        <th class="tabelBorder">Sıra &numero;</th>
                        <th class="tabelBorder">İş və Xidmətin Adı</th>
                        <th class="tabelBorder">Ölçü Vahidi</th>
                        <th class="tabelBorder">Say</th>
                        <th class="tabelBorder">Vahidin Qiyməti</th>
                        <th class="tabelBorder">Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body3">
                    @foreach(json_decode($data->getAttribute('services')) as $service)
                        <tr>
                            <td class="tabelBorder">{{$loop->iteration}}</td>
                            <td class="tabelBorder">{{$service->input1}}</td>
                            <td class="tabelBorder">Ədəd</td>
                            <td class="tabelBorder" id="countCell-{{$loop->iteration}}">{{$service->input3}}</td>
                            <td class="tabelBorder" id="amountCell-{{$loop->iteration}}">{{$service->input4}}</td>
                            <td class="tabelBorder" id="overalCell-{{$loop->iteration}}">{{$service->input3 * $service->input4}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">CƏMİ</td>
                        <td class="tabelBorder sum"></td>
                    </tr>
                    <tr id="vatColumn3">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder vat"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder total"></td>
                    </tr>
                    </tbody>
                </table>
                <br>

                    <p class="invoiceNumbers" @if(!$isSaved) contenteditable="true" @endif>{{$data->getAttribute('invoiceNumbers')}}</p>

                <br>
                <p>Yekun Məbləğ: <span class="total">0</span> AZN (<span class="numberWord"></span>)</p>
                <br><br>
                <div class="float-left text-center imzalar" style="width: 350px; height: 350px">
                    <h6>Təhvil Verdi:</h6>
                    <p class="text-center companyName p-3" style="font-size: 21px">{{$companyName}}</p>

                    <br>
                    <br>
                    <br>
                    <br>
                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                </div>
                <div class="float-right text-center" style="width: 350px; height: 350px">
                    <h6 data-id="{{$data->getRelationValue('financeClients')->getAttribute('id')}}" id="clientId">Təhvil aldı:</h6>
                    <br>
                    <p class="text-center clientName" style="font-size: 18px">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</p>
                    <br>
                    <br>
                    <br>
                    <br>

                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Track if invoice is saved (immutable)
        const isInvoiceSaved = @json($isSaved);
    </script>
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#loginput').css('display', 'none');--}}
{{--        });--}}
{{--    </script>--}}

    @if($company == 'logisticsKapital' || $company == 'logisticsRespublika')
        <script>
            // alert('asd')
            $('#loginput').show();
            $('#brokerinput').hide();
        </script>
    @else
        <script>
            $('#loginput').hide();
            $('#brokerinput').show();
        </script>
    @endif
<script>
    var checkbox = $('#imzala');
    var element = $('.imzalar');
    var copy = $('#copy');
    var copies = $('.copies');
    copies.hide()
    var datam = ''
    function sign () {
        $.ajax({
            url: '/module/signInvoice',
            type: 'POST',
            data: datam,
            success: function(response) {
                console.log('Invoice Imzalandı:', response);
            },
            error: function(error) {
                console.log('Invoice imzalanarkən xəta baş verdi:', error);
                console.log(datam)
            }
        });
    }
    $(document).ready(function () {
        if (checkbox.is(':checked')) {
            element.addClass('imza');
        }
    })
    checkbox.change(function() {
        if (checkbox.is(':checked')) {
            element.addClass('imza');
            datam = {
                id: {{$data->id}},
                sign: 1
            }
            sign()

        } else {
            element.removeClass('imza');
             datam = {
                id: {{$data->id}},
                sign: 0
            }
            sign()
        }
    });
    copy.change(function() {
        if (copy.is(':checked')) {
            copies.show();
        } else {
            copies.hide();
        }
    });

    var savedRows = [];
    function addRow() {
        if (isInvoiceSaved) {
            alert('Bu qaimə saxlanıldıqdan sonra dəyişdirilə bilməz. Dəyişiklik etmək istəyirsinizsə, lütfən qaiməni kopyalayın.');
            return;
        }
        // var selectElement = $('#input1');
        // var selectedOption = selectElement.find('option:selected');
        // var input1 = selectedOption.text();
        // $('#input1l').html("");

        var input1lValue = $('#input1l').val().trim();
        var input1;
        if (input1lValue === '') {
            var selectElement = $('#input1');
            var selectedOption = selectElement.find('option:selected');
            input1 = selectedOption.text();
            $('#input1l').html("");
        } else {
            input1lValue = $('#input1l').val();
        }
        var tableBody = $('#table-body');
        var newRow = tableBody[0].insertRow(tableBody[0].rows.length - 5);

        var input3 = $('#input3').val();
        var input4 = $('#input4').val();

        var tableBody2 = $('#table-body2');
        var newRow2 = tableBody2[0].insertRow(tableBody2[0].rows.length - 3);

        var tableBody3 = $('#table-body3');
        var newRow3 = tableBody3[0].insertRow(tableBody3[0].rows.length - 3);

        var cell1 = newRow.insertCell(0);
        var cell12 = newRow2.insertCell(0);
        var cell13 = newRow3.insertCell(0);

        cell12.textContent = newRow2.parentNode.rows.length - 3;
        cell13.textContent = newRow3.parentNode.rows.length - 3;
        if (input1lValue === '') {
            cell1.textContent = input1;
        } else {
            cell1.textContent = input1lValue;
        }
        var cell2 = newRow.insertCell(1);
        var cell22 = newRow2.insertCell(1);
        var cell23 = newRow3.insertCell(1);
        cell2.textContent = "Ədəd";
        if (input1lValue === '') {
            cell22.textContent = input1;
            cell23.textContent = input1;
        } else {
            cell22.textContent = input1lValue;
            cell23.textContent = input1lValue;
        }
        var cell3 = newRow.insertCell(2);
        var cell32 = newRow2.insertCell(2);
        var cell33 = newRow3.insertCell(2);
        cell3.textContent = input3;
        cell3.classList.add('count');
        cell3.classList.add('tabelBorder');
        // Calculate row index for data-row attribute
        var rowIndex = newRow.rowIndex;
        cell3.setAttribute('data-row', rowIndex);
        cell32.textContent = "Ədəd";
        cell33.textContent = "Ədəd";
        var cell4 = newRow.insertCell(3);
        var cell42 = newRow2.insertCell(3);
        var cell43 = newRow3.insertCell(3);
        cell4.textContent = input4;
        cell4.classList.add('amount');
        cell4.classList.add('tabelBorder');
        cell4.setAttribute('data-row', rowIndex);
        cell42.textContent = input3;
        cell43.textContent = input3;
        var cell5 = newRow.insertCell(4);
        var cell52 = newRow2.insertCell(4);
        var cell53 = newRow3.insertCell(4);
        var overallValue = input3 * input4;
        cell5.textContent = overallValue.toFixed(2);
        cell5.classList.add('overal');
        cell5.classList.add('miktar-hucre');
        cell5.classList.add('tabelBorder');
        cell52.textContent = input4;
        cell53.textContent = input4;
        var cell6 = newRow.insertCell(5);
        var cell62 = newRow2.insertCell(5);
        var cell63 = newRow3.insertCell(5);
        // Classes already added above for cell3, cell4, cell5
        cell1.classList.add('tabelBorder');
        cell12.classList.add('tabelBorder');
        cell13.classList.add('tabelBorder');
        cell2.classList.add('tabelBorder');
        cell22.classList.add('tabelBorder');
        cell23.classList.add('tabelBorder');
        cell32.classList.add('tabelBorder');
        cell33.classList.add('tabelBorder');
        cell42.classList.add('tabelBorder');
        cell43.classList.add('tabelBorder');
        cell52.classList.add('tabelBorder');
        cell53.classList.add('tabelBorder');
        cell6.classList.add('tabelBorder');
        cell62.classList.add('tabelBorder');
        cell63.classList.add('tabelBorder');
        cell62.textContent = input3 * input4;
        cell63.textContent = input3 * input4;
        var deleteButton = $('<button>', {
            text: 'Sil',
            class: 'btn btn-danger',
            click: function() {
                deleteRow(this);
            }
        });
        $(cell6).append(deleteButton);
        var newRowData = {
            input1: input1lValue === '' ? input1 : input1lValue,
            input3: input3,
            input4: input4
        };

        savedRows.push(newRowData);
        $('#input1').val('');
        $('#input3').val('');
        $('#input4').val('');
        calculateTotal();

        var numberWord = $('.numberWord');
        numberWord.each(function() {
            $(this).html(convertToWords($('#total').html()).toUpperCase());
        });

        setEditableContent(newRow);
        setEditableContent(newRow2);
        setEditableContent(newRow3);
    }
    function setEditableContent(row) {
        if (isInvoiceSaved) {
            return; // Don't make editable if invoice is saved
        }
        var cells = row.cells;
        for (var i = 0; i < cells.length; i++) {
            var cell = cells[i];
            if (!cell.classList.contains('tabelBorder') || cell.querySelector('button')) {
                continue; // Skip cells with buttons or special classes
            }
            cell.setAttribute('contenteditable', 'true');
        }
    }
    savedRows = savedRows.concat({!! $data->getAttribute('services') !!})

        // Enable editing only for new invoices
        // Use event delegation to handle dynamically added rows
        if (!isInvoiceSaved) {
            $(document).on('input', '#table-body .count', function() {
                var $cell = $(this);
                var rowIndex = $cell.closest('tr').index();
                var newValue = parseFloat($cell.text().trim()) || 0;
                
                // Update savedRows if it exists, otherwise create entry
                if (!savedRows[rowIndex]) {
                    var input1 = $cell.closest('tr').find('td:first').text().trim();
                    var input4 = parseFloat($cell.closest('tr').find('.amount').text().trim()) || 0;
                    savedRows[rowIndex] = {
                        input1: input1,
                        input3: newValue,
                        input4: input4
                    };
                } else {
                    savedRows[rowIndex].input3 = newValue;
                }
                
                // Recalculate totals
                calculateTotal();
            });

            $(document).on('input', '#table-body .amount', function() {
                var $cell = $(this);
                var rowIndex = $cell.closest('tr').index();
                var newAmount = parseFloat($cell.text().trim()) || 0;
                
                // Update savedRows if it exists, otherwise create entry
                if (!savedRows[rowIndex]) {
                    var input1 = $cell.closest('tr').find('td:first').text().trim();
                    var input3 = parseFloat($cell.closest('tr').find('.count').text().trim()) || 0;
                    savedRows[rowIndex] = {
                        input1: input1,
                        input3: input3,
                        input4: newAmount
                    };
                } else {
                    savedRows[rowIndex].input4 = newAmount;
                }
                
                // Recalculate totals
                calculateTotal();
            });
        }

    function deleteRow(button) {
        if (isInvoiceSaved) {
            alert('Bu qaimə saxlanıldıqdan sonra dəyişdirilə bilməz. Dəyişiklik etmək istəyirsinizsə, lütfən qaiməni kopyalayın.');
            return;
        }
        var row = $(button).closest('tr');
        var rowIndex = row.index() + 1;

        var tableBody = $('#table-body');
        var tableBody2 = $('#table-body2');
        var tableBody3 = $('#table-body3');

        tableBody[0].deleteRow(rowIndex - 1);
        tableBody2[0].deleteRow(rowIndex - 1);
        tableBody3[0].deleteRow(rowIndex - 1);

        var rows2 = tableBody2[0].rows;
        var rows3 = tableBody3[0].rows;

        for (var i = rowIndex + 3; i < rows2.length; i++) {
            var row2 = rows2[i];
            var row3 = rows3[i];

            row2.cells[0].textContent = i + 1;
            row3.cells[0].textContent = i + 1;
        }
        savedRows.splice(rowIndex - 1, 1);

        calculateTotal();
    }
    function deleteOldRow(iteration) {
        if (isInvoiceSaved) {
            alert('Bu qaimə saxlanıldıqdan sonra dəyişdirilə bilməz. Dəyişiklik etmək istəyirsinizsə, lütfən qaiməni kopyalayın.');
            return;
        }
        var row = $('[data-row="' + iteration + '"]').closest('tr');

        var tableBody = $('#table-body');
        var tableBody2 = $('#table-body2');
        var tableBody3 = $('#table-body3');

        var rowIndex = row.index();

        tableBody[0].deleteRow(rowIndex);
        tableBody2[0].deleteRow(rowIndex);
        tableBody3[0].deleteRow(rowIndex);

        var rows2 = tableBody2[0].rows;
        var rows3 = tableBody3[0].rows;

        for (var i = rowIndex + 3; i < rows2.length; i++) {
            var row2 = rows2[i];
            var row3 = rows3[i];

            row2.cells[0].textContent = i + 1;
            row3.cells[0].textContent = i + 1;
        }

        savedRows.splice(rowIndex, 1); // Değişiklik burada
        calculateTotal();
    }
    function calculateTotal() {
        var miktarHucres = $('.miktar-hucre');
        var sum1 = 0;

        miktarHucres.each(function() {
            var miktarHucre = $(this);
            var value = parseFloat(miktarHucre.text());
            if (!isNaN(value)) {
                sum1 += value;
            }
        });

        var sumCell = $('#sum');
        var vatCell = $('#vat');
        var totalCell = $('#total');

        var edvCompany = $('#companies').val();
        var edv = (edvCompany !== 'mbrokerRespublika' && edvCompany !== 'mtechnologiesRespublika' && edvCompany !== 'garantRespublika' && edvCompany !== 'garantKapital' && edvCompany !== 'mbrokerKapital' && edvCompany !== 'mtechnologiesKapital') ? 1 : 1.18;

        var overallElements = $(".overal");
        var sum2 = 0;

        overallElements.each(function() {
            var $row = $(this).closest("tr");
            var count = parseFloat($row.find(".count").text().trim()) || 0;
            var amount = parseFloat($row.find(".amount").text().trim()) || 0;
            var overallValue = count * amount;
            if (!isNaN(overallValue) && overallValue > 0) {
                $(this).text(overallValue.toFixed(2));
                sum2 += overallValue;
            }
        });

        var getCompany = $("#getCompany").html();

        var sumElement = $(".sum");
        var vatElement = $(".vat");
        var totalElement = $(".total");

         var sum = sum1 +sum2
        sumCell.text(sum.toFixed(2));
        vatCell.text((sum * 0.18).toFixed(2));
        totalCell.text((sum * edv).toFixed(2));
        $('#sum2').html(sum.toFixed(2));
        $('#vat2').html((sum * 0.18).toFixed(2));
        $('#total2').html((sum * edv).toFixed(2));
        $('#sum3').html((sum * edv).toFixed(2));
        $('#vat3').html((sum * 0.18).toFixed(2));
        $('#total3').html((sum * edv).toFixed(2));
        $('#total4').html((sum * edv).toFixed(2));
        $('#total5').html((sum * edv).toFixed(2));
        $('#total6').html((sum * edv).toFixed(2));
        $('#total7').html((sum * edv).toFixed(2));

        if (getCompany !== '\"Mobil Broker\" MMC' && getCompany !== '\"Garant Broker\" MMC' && getCompany !== '\"Mobil Technologies\" MMC') {
            $("#vatColumn, #vatColumn2, #vatColumn3").hide();
            var rate = 1;
        } else {
            vatElement.text((sum * 0.18).toFixed(2));
            var rate = 1.18;
        }

        sumElement.text(sum.toFixed(2));
        totalElement.text((sum * rate).toFixed(2));

        var numberWord = $('.numberWord');
        numberWord.html(convertToWords($('#total').html()).toUpperCase());

    }

    $(".count, .amount").on("input", function() {
        calculateTotal();
    });

    calculateTotal();

    $('#table-body .count, #table-body .amount').on('input', function() {
        var row = $(this).data('row');
        var count = parseFloat($('#table-body td.count[data-row="' + row + '"]').text()) || 0;
        var amount = parseFloat($('#table-body td.amount[data-row="' + row + '"]').text()) || 0;
        var overall = count * amount;

        var countCell = "#countCell-" + row
        var amountCell = "#amountCell-" + row
        var overallCell = "#overalCell-" + row
        var count2Cell = "#countCell2-" + row
        var amount2Cell = "#amountCell2-" + row
        var overall2Cell = "#overalCell2-" + row
        $(countCell).text(count)
        $(amountCell).text(amount)
        $(overallCell).text(overall)
        $(count2Cell).text(count)
        $(amount2Cell).text(amount)
        $(overall2Cell).text(overall)
    });
     $('#invoiceNo').on('input', function() {
        var invoiceNo = $(this).text();
        $('.invoiceNo').text(invoiceNo)
    });
     $('#invoiceDate').on('input', function() {
        var invoiceDate = $(this).text();
        $('.invoiceDate').text(invoiceDate)
    });

    function convertToWords(number) {
        const units = ['', 'bir', 'iki', 'üç', 'dörd', 'beş', 'altı', 'yeddi', 'səkkiz', 'doqquz'];
        const tens = ['', 'on', 'iyirmi', 'otuz', 'qırx', 'əlli', 'altmış', 'yetmiş', 'səksən', 'doxsan'];
        const bigs = ['', 'min', 'milyon', 'milyard', 'trilyon', 'katrilyon'];

        let wholePart = Math.floor(number);
        let decimalPart = Math.round((number - wholePart) * 100);

        let wholePartWords = '';
        let decimalPartWords = '';

        if (wholePart === 0) {
            wholePartWords = 'sıfır';
        } else {
            let chunkCount = 0;
            while (wholePart > 0) {
                if (wholePart % 1000 !== 0) {
                    let chunk = numberToWordsHelper(wholePart % 1000) + ' ' + bigs[chunkCount];
                    wholePartWords = chunk + ' ' + wholePartWords;
                }
                wholePart = Math.floor(wholePart / 1000);
                chunkCount++;
            }
        }

        if (decimalPart > 0) {
            decimalPartWords = numberToWordsHelper(decimalPart) + ' qəpik';
        }

        return wholePartWords.trim() + ' manat ' + decimalPartWords;
    }

    function numberToWordsHelper(number) {
        const units = ['', 'bir', 'iki', 'üç', 'dörd', 'beş', 'altı', 'yeddi', 'səkkiz', 'doqquz'];
        const tens = ['', 'on', 'iyirmi', 'otuz', 'qırx', 'əlli', 'altmış', 'yetmiş', 'səksən', 'doxsan'];
        const hundreds = ['', 'yüz', 'iki yüz', 'üç yüz', 'dörd yüz', 'beş yüz', 'altı yüz', 'yeddi yüz', 'səkkiz yüz', 'doqquz yüz'];

        let result = '';

        let hundredsDigit = Math.floor(number / 100);
        let tensDigit = Math.floor((number % 100) / 10);
        let unitsDigit = number % 10;

        if (hundredsDigit > 0) {
            result += hundreds[hundredsDigit] + ' ';
        }

        if (tensDigit > 0) {
            result += tens[tensDigit] + ' ';
        }

        if (unitsDigit > 0) {
            result += units[unitsDigit] + ' ';
        }

        return result;
    }

    function createInvoice() {
        if (isInvoiceSaved) {
            alert('Bu qaimə artıq saxlanılıb. Yeni qaimə yaratmaq üçün qaiməni kopyalayın.');
            return;
        }
        
        // Collect all current service rows from the table
        var services = [];
        $('#table-body tr').each(function() {
            var $row = $(this);
            // Skip header rows, form input row, and summary rows (CƏMİ, ƏDV, YEKUN)
            var rowText = $row.text().trim();
            if (rowText.includes('CƏMİ') || rowText.includes('ƏDV') || rowText.includes('YEKUN')) {
                return; // Skip summary rows
            }
            
            // Check if row has count and amount cells (service row)
            var $countCell = $row.find('.count');
            var $amountCell = $row.find('.amount');
            
            if ($countCell.length > 0 && $amountCell.length > 0) {
                var input1 = $row.find('td:first').text().trim();
                var input3 = parseFloat($countCell.text().trim()) || 0;
                var input4 = parseFloat($amountCell.text().trim()) || 0;
                
                // Validate service data
                if (input1 && !isNaN(input3) && input3 > 0 && !isNaN(input4) && input4 > 0) {
                    services.push({
                        input1: input1,
                        input3: input3,
                        input4: input4
                    });
                }
            }
        });
        
        console.log('Collected services:', services); // Debug log
        
        if (services.length === 0) {
            alert('Ən azı bir xidmət əlavə edin.');
            return;
        }
        console.log(services)
        $.ajax({
            url: '/module/createFinanceInvoice',
            type: 'POST',
            data: {
                company: $('#companyName').attr('data-company'),
                client: $('#clientId').attr('data-id'),
                invoiceNo: $('#invoiceNo').text(),
                invoiceDate: $('#invoiceDate').text(),
                paymentType: $('#paymentType').text(),
                protocolDate: $('#protocolDate').text(),
                contractNo: $('#contractNo').text(),
                contractDate: $('#contractDate').text(),
                invoiceNumbers: $('.invoiceNumbers').first().text(),
                services: services,
            },
            success: function(response) {
                console.log('Invoice yaratıldı:', response);
                alert('Qaimə uğurla yaradıldı!');
                // Redirect to the new invoice page (it will have an ID now)
                if (response && response.id) {
                    window.location.href = '/module/financeInvoice/' + response.id;
                } else {
                    // Reload page to show saved invoice (now immutable)
                    window.location.reload();
                }
            },
            error: function(error) {
                console.log('Invoice yaratılırken hata oluştu:', error);
            }
        });
    }

    function printCard1() {
        $('#print-area').hide();
        $('#form-area').hide();
        $('.btn-danger').each(function() {
            $(this).parent().hide();
        });

        var printContent = $('#printCard1').html();
        var originalContent = $('body').html();

        $('body').html(printContent);
        window.print();
        $('body').html(originalContent);
    }

    function printCard2() {
        var printContent = $('#printCard2').html();
        var originalContent = $('body').html();

        $('body').html(printContent);
        window.print();
        $('body').html(originalContent);
    }

    function printCard3() {
        var printContent = $('#printCard3').html();
        var originalContent = $('body').html();

        $('body').html(printContent);
        window.print();
        $('body').html(originalContent);
    }

        function printCards() {
        $('#print-area').hide();
        $('#form-area').hide();
        $('.btn-danger').each(function() {
        $(this).parent().hide();
    });

        var printContent1 = $('#printCard1').html();
        var printContent2 = $('#printCard2').html();
        var printContent3 = $('#printCard3').html();
        var originalContent = $('body').html();

        var combinedContent = printContent1 + '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>' + printContent2 + '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>' + printContent3;
        $('body').html(combinedContent);
        window.print();
        $('body').html(originalContent);
    }
</script>

@endsection