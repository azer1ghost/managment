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
        <button onclick="createInvoice()" class="btn btn-success float-right">+</button>
        <div class="card" id="printCard1">
            <div class="card-body">
                <h2 class="text-center companyName" data-company="{{$company}}" id="companyName">{{$companyName}}</h2>
                <h3 class="text-center">HESAB FAKTURA &numero; <span id="invoiceNo" contenteditable="true">{{$data->getAttribute('invoiceNo')}}</span></h3>
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
                        <td class="border tabelBorder">Tarix: <span id="invoiceDate" contenteditable="true">{{$data->getAttribute('invoiceDate')}}</span></td>
                        <td class="border tabelBorder">&numero; <span class="invoiceNo">{{$data->getAttribute('invoiceNo')}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0 tabelBorder">VÖEN: <span class="clientVoen">{{$data->getRelationValue('financeClients')->getAttribute('voen')}}</span></td>
                        <td class="border tabelBorder">Ödəmə növü: <span id="paymentType" contenteditable="true">{{$data->getAttribute('paymentType')}}</span></td>
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
                        <td class="tabelBorder count" contenteditable="true" data-row="{{$loop->iteration}}">{{$service->input3}}</td>
                        <td class="tabelBorder amount" contenteditable="true" data-row="{{$loop->iteration}}">{{$service->input4}}</td>
                        <td class="tabelBorder overal">{{$service->input3 * $service->input4}}</td>
                        <td class="tabelBorder"><a  onclick="deleteOldRow({{$loop->iteration}})" class="btn btn-danger">Sil</a></td>

                    </tr>
                    @endforeach
                    <tr id="form-area">
                        <td>
                            <select id="input1" class="form-control">
                                <option>Elektron GB-nin tərtib olunması xidməti</option>
                                <option>Elektron Qısa İdxal GB-nin tərtib olunması xidməti</option>
                                <option>CMR-in tərtib olunması xidməti</option>
                                <option>Gömrük kodunun müəyyən edilməsi</option>
                                <option>Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin hazırlanması</option>
                                <option>Təmsil etdiyi şəxsin tapşırığı əsasında yüklərin və nəqliyyat vasitələrinin tam gömrük rəsmiləşdirilməsi</option>
                                <option>Gömrük rüsumlarının əvvəlcədən hesablanması</option>
                                <option>Təmsilçilik xidməti</option>
                                <option>Qismən Təmsilçilik xidməti</option>
                                <option>Printerlərə texniki baxışın göstərilməsi</option>
                                <option>Serverlərə texniki baxışın göstərilməsi</option>
                                <option>Kompüterlərə texniki baxışın göstərilməsi</option>
                            </select>
                        </td>
                        <td>Ədəd</td>
                        <td><input type="text" class="form-control" id="input3"></td>
                        <td><input type="text" class="form-control" id="input4"></td>
                        <td id="print-area"><button onclick="addRow()" class="btn btn-primary">+</button></td>
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
                <p class="float-right" id="protocolDate" contenteditable="true">{{$data->getAttribute('protocolDate')}}</p>
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
                    <h6 data-id="{{$data->getRelationValue('financeClients')->getAttribute('id')}}" id="clientId">Təhvil aldı:</h6>
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
@section('scripts')

<script>
    var savedRows = [];
    function addRow() {
        var selectElement = $('#input1');
        var selectedOption = selectElement.find('option:selected');
        var input1 = selectedOption.text();
        $('#input1l').html("");

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
        cell1.textContent = input1;
        var cell2 = newRow.insertCell(1);
        var cell22 = newRow2.insertCell(1);
        var cell23 = newRow3.insertCell(1);
        cell2.textContent = "Ədəd";
        cell22.textContent = input1;
        cell23.textContent = input1;
        var cell3 = newRow.insertCell(2);
        var cell32 = newRow2.insertCell(2);
        var cell33 = newRow3.insertCell(2);
        cell3.textContent = input3;
        cell32.textContent = "Ədəd";
        cell33.textContent = "Ədəd";
        var cell4 = newRow.insertCell(3);
        var cell42 = newRow2.insertCell(3);
        var cell43 = newRow3.insertCell(3);
        cell4.textContent = input4;
        cell42.textContent = input3;
        cell43.textContent = input3;
        var cell5 = newRow.insertCell(4);
        var cell52 = newRow2.insertCell(4);
        var cell53 = newRow3.insertCell(4);
        cell5.textContent = input3 * input4;
        cell52.textContent = input4;
        cell53.textContent = input4;
        cell5.classList.add('miktar-hucre');
        var cell6 = newRow.insertCell(5);
        var cell62 = newRow2.insertCell(5);
        var cell63 = newRow3.insertCell(5);
        cell1.classList.add('tabelBorder');
        cell12.classList.add('tabelBorder');
        cell13.classList.add('tabelBorder');
        cell2.classList.add('tabelBorder');
        cell22.classList.add('tabelBorder');
        cell23.classList.add('tabelBorder');
        cell3.classList.add('tabelBorder');
        cell32.classList.add('tabelBorder');
        cell33.classList.add('tabelBorder');
        cell4.classList.add('tabelBorder');
        cell42.classList.add('tabelBorder');
        cell43.classList.add('tabelBorder');
        cell5.classList.add('tabelBorder');
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
            input1: input1,
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
    }

    function deleteRow(button) {
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

        for (var i = rowIndex - 1; i < rows2.length; i++) {
            var row2 = rows2[i];
            var row3 = rows3[i];

            row2.cells[0].textContent = i + 1;
            row3.cells[0].textContent = i + 1;
        }

        calculateTotal();
    }
    function deleteOldRow(iteration) {
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

        for (var i = rowIndex; i < rows2.length; i++) {
            var row2 = rows2[i];
            var row3 = rows3[i];

            row2.cells[0].textContent = i + 1;
            row3.cells[0].textContent = i + 1;
        }

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
        var edv = (edvCompany !== 'mbrokerRespublika' && edvCompany !== 'mtechnologiesRespublika' && edvCompany !== 'garantRespublika' && edvCompany !== 'garantKapital' && edvCompany !== 'mbrokerKapital') ? 1 : 1.18;

        var overallElements = $(".overal");
        var sum2 = 0;

        overallElements.each(function() {
            var count = parseInt($(this).closest("tr").find(".count").text());
            var amount = parseFloat($(this).closest("tr").find(".amount").text());
            var overallValue = count * amount;
            $(this).text(overallValue.toFixed(2));
            sum2 += overallValue;
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
        var services = savedRows.concat({!! $data->getAttribute('services') !!});
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
                contractNo: $('.contractNo').text(),
                contractDate: $('.contractDate').text(),
                services: services
            },
            success: function(response) {
                console.log('Invoice yaratıldı:', response);
                $('#invoiceCreate').hide()
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
</script>

@endsection