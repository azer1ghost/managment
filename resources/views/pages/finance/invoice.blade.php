@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

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
    </style>
@endsection

@section('content')
@php
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
     } else if ($company == 'garantKapital') {
         $companyName = "\"Garant Broker\" MMC";
         $voen = "1803974481";
         $hh = "AZ56AIIB400500D9447227965229";
         $mh = "AZ37NABZ01350100000000001944";
         $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
         $kod = "201412";
         $bvoen = "9900003611";
         $swift = "AIIBAZ2XXXX";
         $who = "Alişan Cəlilov Maqsud oğlu";
         $whoFooter = "A.M.Cəlilov";
     } else if ($company == 'garantRespublika') {
         $companyName = "\"Garant Broker\" MMC";
         $voen = "1803974481";
         $hh = "AZ95BRES00380394401114875001";
         $mh = "AZ80NABZ01350100000000014944";
         $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
         $kod = "201412";
         $bvoen = "9900001901";
         $swift = "BRESAZ22";
         $who = "Alişan Cəlilov Maqsud oğlu";
         $whoFooter = "A.M.Cəlilov";
     } else if ($company == 'rigelKapital') {
         $companyName = "Rigel Group";
         $voen = "1805978211";
         $hh = "AZ61AIIB400500E9445911817229";
         $mh = "AZ37NABZ01350100000000001944";
         $bank = "KAPITAL BANK ASC KOB mərkəz filialı";
         $kod = "201412";
         $bvoen = "9900003611";
         $swift = "AIIBAZ2XXXX";
         $who = "Xəlilova Lamiyə Fərhad qızı";
         $whoFooter = "L.İ.Xəlilova";

     } else if ($company == 'rigelRespublika') {
         $companyName = "Rigel Group";
         $voen = "1805978211";
         $hh = "AZ43BRES00380394401162048201";
         $mh = "AZ80NABZ01350100000000014944";
         $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
         $kod = "507547";
         $bvoen = "9900001901";
         $swift = "BRESAZ22";
         $who = "Xəlilova Lamiyə Fərhad qızı";
         $whoFooter = "L.İ.Xəlilova";

     } else if ($company == 'mindRespublika') {
         $companyName = "\"Mind Services\" MMC";
         $voen = "1506046601";
         $hh = "AZ88BRES00380394401162079401";
         $mh = "AZ80NABZ01350100000000014944";
         $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
         $kod = "507547";
         $bvoen = "9900001901";
         $swift = "BRESAZ22";
         $who = "Əliyev Fuad Rasim oğlu";
         $whoFooter = "F.R.Əliyev";

     } else if ($company == 'asazaRespublika') {
         $companyName = "\"ASAZA FLKS\" MMC";
         $voen = "1805091391";
         $hh = "AZ80BRES00380394401196199101";
         $mh = "AZ80NABZ01350100000000014944";
         $bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
         $kod = "507547";
         $bvoen = "9900001901";
         $swift = "BRESAZ22";
         $who = "Fərhad İbrahimli Əli oğlu";
         $whoFooter = "F.Ə.İbrahimli";

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
         $whoFooter = "L.İ.Xəlilova";
         $representer = "Ekspeditor";
         }
    @endphp
    <div class="container">
        <br>
        <button onclick="printCard1()" class="btn btn-primary float-right">Print</button>
        <div class="card" id="printCard1">
            <div class="card-body">
                <h2 class="text-center companyName" id="companyName">{{$companyName}}</h2>
                <h3 class="text-center">HESAB FAKTURA &numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></h3>
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
                        <td class="border tabelBorder">Tarix: {{$data->getAttribute('invoiceDate')}}<span class="invoiceDate"></span></td>
                        <td class="border tabelBorder">&numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0 tabelBorder">VÖEN: <span class="clientVoen">{{$data->getRelationValue('financeClients')->getAttribute('voen')}}</span></td>
                        <td class="border tabelBorder">Ödəmə növü: <span id="paymentType">{{$data->getAttribute('paymentType')}}</span></td>
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
                        <td class="tabelBorder">{{$service->input1}}</td>
                        <td class="tabelBorder">Ədəd</td>
                        <td class="tabelBorder count">{{$service->input3}}</td>
                        <td class="tabelBorder amount">{{$service->input4}}</td>
                        <td class="tabelBorder overal">{{$service->input3 * $service->input4}}</td>
                    </tr>
                    @endforeach
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
                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right protocolDate">{{$data->getAttribute('protocolDate')}}</p>
                <br>
                <br>
                <br>
                <h3 class="mb-2 text-center">QİYMƏT RAZILAŞDIRMA PROTOKOLU &numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></h3>
                <h6 class="mb-2 text-center"><span class="companyName">{{$companyName}}</span> və <span class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</span> arasında bağlanan <span class="contractDate">{{$data->getAttribute('contractDate')}}</span> tarixli</h6>
                <h6 class="mb-2 text-center">&numero; <span class="contractNo">{{$data->getAttribute('contractNo')}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Müqaviləyə əlavə</h6>
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
                            <td class="tabelBorder">{{$service->input3}}</td>
                            <td class="tabelBorder">{{$service->input4}}</td>
                            <td class="tabelBorder">{{$service->input3 * $service->input4}}</td>
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
                    <p>Director: <span id="who">{{$who}}</span></p>
                    <br>
                    <br>
                    <p>İmza, möhür</p>
                    <br>
                    <br>
                    <br>
                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
                <div class="float-right text-center">
                    <h6>Sifarişçi</h6>
                    <br>
                    <p class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</p>
                    <p>VÖEN: <span class="clientVoen">{{$data->getRelationValue('financeClients')->getAttribute('voen')}}</span></p>
                    <p>H/H: <span class="clienthh">{{$data->getRelationValue('financeClients')->getAttribute('hh')}}</span></p>
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
                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right invoiceDate">{{$data->getAttribute('invoiceDate')}}</p>
                <br>
                <br>
                <br>
                <h6 class=" mb-2 text-center"><span class="companyName">{{$companyName}}</span> və <span class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</span> arasında bağlanan &numero; <span class="contractDate">{{$data->getAttribute('contractDate')}}</span>  tarixli</h6>
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
                            <td class="tabelBorder">{{$service->input3}}</td>
                            <td class="tabelBorder">{{$service->input4}}</td>
                            <td class="tabelBorder">{{$service->input3 * $service->input4}}</td>
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
                <br>
                <p>Yekun Məbləğ: <span class="total">0</span> AZN (<span class="numberWord"></span>)</p>
                <br><br>
                <div class="float-left text-center">
                    <h6>Təhvil Verdi:</h6>
                    <br>
                    <br>
                    <p class="text-center companyName">{{$companyName}}</p>
                    <br>
                    <br>
                    <br>
                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
                <div class="float-right text-center">
                    <h6>Təhvil aldı:</h6>
                    <br>
                    <br>
                    <p class="clientName">{{$data->getRelationValue('financeClients')->getAttribute('name')}}</p>
                    <br><br><br>

                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
            </div>

        </div>
    </div>
@endsection
<script>

    window.onload = function() {

        var overallElements = document.getElementsByClassName("overal");

        var sum = 0; // Toplamı tutacak değişken

        for (var i = 0; i < overallElements.length; i++) {
            var overallValue = parseInt(overallElements[i].innerText);
            sum += overallValue;
        }
        const getCompany = document.getElementById("getCompany").innerHTML
        var sumElement = document.getElementsByClassName("sum");
        var vatElement = document.getElementsByClassName("vat");
        var totalElement = document.getElementsByClassName("total");

        if (getCompany !== '\"Mobil Broker\" MMC' &&  getCompany !== '\"Garant Broker\" MMC'){
            document.getElementById("vatColumn").style.display = "none";
            document.getElementById("vatColumn2").style.display = "none";
            document.getElementById("vatColumn3").style.display = "none";
            var rate = 1
        }else {
            for (var i = 0; i < vatElement.length; i++) {
                vatElement[i].innerText = (sum * 0.18).toFixed(2);
            }
            var rate = 1.18

        }

        for (var i = 0; i < sumElement.length; i++) {
            sumElement[i].innerText = sum.toFixed(2);
        }

        for (var i = 0; i < totalElement.length; i++) {
            totalElement[i].innerText = (sum * rate).toFixed(2);
        }

        var numberWord = document.getElementsByClassName('numberWord');
        for (var i = 0; i < numberWord.length; i++) {
            numberWord[i].innerHTML = convertToWords(document.getElementById('total').innerHTML).toUpperCase()
        }

    };

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

        function printCard1() {
            var printContent = document.getElementById('printCard1').innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
        function printCard2() {
            var printContent = document.getElementById('printCard2').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
        function printCard3() {
            var printContent = document.getElementById('printCard3').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
