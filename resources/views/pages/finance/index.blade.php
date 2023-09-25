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
    <div class="text-center container">
        <div class="card">
            <div class="row col-12 justify-content-center"><a href="{{ route('invoices') }}" class="btn btn-dark col-5 m-2">Şablonlar</a>
                <a href="{{ route('financeClients') }}" class="btn btn-google col-5 m-2">Müştərilər</a></div>
            <h3>Rekvizitlər</h3>
            <div class="row col-12 justify-content-center">

                <select class="form-control col-4 mb-2 mr-2" id="companies" onchange="changeCompany()">
                    <option value="">Şirkət Seçin</option>
                    <option value="mbrokerKapital">Mobil Broker Kapital</option>
                    <option value="mbrokerRespublika">Mobil Broker Bank Respublika</option>
                    <option value="mgroupRespublika">Mobil Group Bank Respublika</option>
                    <option value="garantKapital">Garant Broker Kapital</option>
                    <option value="garantRespublika">Garant Broker Bank Respublika</option>
                    <option value="rigelRespublika">Rigel Bank Respublika</option>
                    <option value="rigelKapital">Rigel Kapital</option>
                    <option value="mindRespublika">Mind Bank Respublika</option>
                    <option value="mindKapital">Mind Kapital</option>
                    <option value="asazaRespublika">Asaza Bank Respublika</option>
                    <option value="tgroupKapital">Tedora Group Kapital</option>
                    <option value="dgroupKapital">Declare Group Kapital</option>
                    <option value="mtechnologiesRespublika">Mobil Technologies Bank Respublika</option>
                    <option value="logisticsKapital">Mobil Logistics Kapital</option>
                    <option value="logisticsRespublika">Mobil Logistics Bank Respublika</option>
                </select>
                <select id="clientSelect" onchange="changeClient()" class=" col-4 mt-4 select2">
                    <option value="">Müştəri seç</option>
                </select>
            </div>

            <hr>
            <h3>Müştəri Məlumatları</h3>
            <div class="row col-12 justify-content-center">
                <input class="form-control col-3 m-2" id="clientNameInput" placeholder="Müştəri Adı"
                       onchange="clientName()">
                <input class="form-control col-3 m-2" id="clientVoenInput" placeholder="Müştəri Voen"
                       oninput="clientVoen()">
                <input class="form-control col-3 m-2" id="clienthhInput" placeholder="Müştəri H/N"
                       oninput="clienthh()">
                <input class="form-control col-3 m-2" id="clientmhInput" placeholder="Müştəri M/H"
                       oninput="clientmh()">
                <input class="form-control col-3 m-2" id="clientCodeInput" placeholder="Müştəri Bank Kod"
                       oninput="clientCode()">
                <input class="form-control col-3 m-2" id="clientBankInput" placeholder="Müştəri Bank Adı"
                       oninput="clientBank()">
                <input class="form-control col-3 m-2" id="clientBvoenInput" placeholder="Müştəri Bank VÖEN"
                       oninput="clientBvoen()">
                <input class="form-control col-3 m-2" id="clientSwiftInput" placeholder="Müştəri Bank S.W.I.F.T"
                       oninput="clientSwift()">
                <input class="form-control col-3 m-2" id="clientWhoInput" placeholder="Müştəri Vəzifəsi : Adı"
                       oninput="clientWho()">
                <button class="btn btn-success col-3 m-2" id="createClient" onclick="createClient()">Müştəri Məlumatlarını Yadda Saxla</button>
            </div>

            <hr>

            <h3 class="m-2">Hesab Faktura</h3>
                <div class="row col-12 justify-content-center">
                <input class="form-control col-3 m-2" id="invoiceNoInput" placeholder="Hesab faktura nömrəsi"
                       oninput="invoiceNo()">
                <input class="form-control col-3 m-2" id="invoiceDateInput" value="{{now()->format('d.m.Y')}}" placeholder="Hesab faktura tarixi"
                       oninput="invoiceDate()">

                <select class="form-control col-3 m-2" id="paymentTypeSelect" onchange="paymentType()">
                    <option value="">Ödəmə növü</option>
                    <option value="Köçürmə">Köçürmə</option>
                    <option value="nağd">Nağd</option>
                </select>
                <input class="form-control col-3 m-2" id="invoiceNumbersInput" placeholder="Sorğu nömrələri"  oninput="invoiceNumbers()">
            </div>
            <hr>
            <h3 class="m-2">Qiymət Razılaşdırma Protokolu</h3>
            <div class="row col-12 justify-content-center">
                <input class="form-control col-3 m-2" value="{{\Illuminate\Support\Carbon::now()->subDay()->format('d.m.Y')}}" id="protocolDateInput"
                       placeholder="Protokol tarixi" oninput="protocolDate()">
                <input class="form-control col-3 m-2" id="contractNoInput" placeholder="Müqavilə nömrəsi"
                       oninput="contractNo()">
                <input class="form-control col-3 m-2" id="contractDateInput" value="" placeholder="Müqavilə tarixi"
                       oninput="contractDate()">
            </div>
            <hr>


        </div>

    </div>
    <div class="container">
        <br>
        <button onclick="printCards()" class="btn btn-primary float-right">Print Cards</button>
        <button onclick="printCard1()" class="btn btn-primary float-right">Print</button>
        <button class="btn btn-success col-3 m-2" id="invoiceCreate" onclick="createInvoice()">Sənədləri Yadda Saxla</button>
        <div class="card" id="printCard1">
            <div class="card-body">
                <h2 class="text-center companyName" id="companyName"></h2>
                <h3 class="text-center">HESAB FAKTURA &numero; <span class="invoiceNo"></span></h3>
                <h6 class=" mb-2"><span class="companyName"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VOEN: <span class="voen"></span></h6>
                <h6 class=" mb-2">H/H: <span class="hh"></span></h6>
                <h6 class=" mb-2">M/H <span class="mh"></span></h6>
                <h6 class=" mb-2">BANK: <span class="bank"></span> KOD: <span class="kod"></span></h6>
                <h6 class=" mb-2">BANK VOEN: <span class="bvoen"></span> &nbsp;&nbsp;  S.W.I.F.T: <span class="swift"></span></h6>
                <h1 class="text-center companyName"></h1>
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
                        <td colspan="2" class="border border-bottom-0 tabelBorder clientName"></td>
                        <td class="border tabelBorder">Tarix: <span class="invoiceDate"></span></td>
                        <td class="border tabelBorder">&numero; <span class="invoiceNo"></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0 tabelBorder">VÖEN: <span class="clientVoen"></span></td>
                        <td class="border tabelBorder">Ödəmə növü: <span id="paymentType">Köçürmə</span></td>
                        <td class="border tabelBorder" id="total4"></td>
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
                                <option>Gömrük rəsmiləşdirilməsinin həyata keçirilməsi zamanı gömrük məmuru ilə əlaqə yaradılması xidməti</option>
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
                        <td  class="tabelBorder" id="sum"></td>
                    </tr>
                    <tr id="vatColumn">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder" id="vat"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder" id="total"></td>
                    </tr>
                    <tr>
                        <td class="tabelBorder" colspan="5" id="total-text">Yekun Məbləğ: <span id="total5">0</span> AZN (<span class="numberWord"></span>)</td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <p class="invoiceNumbers"></p>
                <br>

                <p class="float-left"><span class="companyName"></span>-nin direktoru</p>
                <p class="float-right" id="who-footer"></p>
            </div>

        </div>
    </div>
    <div class="container">
        <br>
        <button onclick="printCard2()" class="btn btn-primary float-right">Print</button>
        <div class="card" id="printCard2">
            <div class="card-body">
                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right protocolDate"></p>
                <br>
                <br>
                <br>
                <h3 class="mb-2 text-center">QİYMƏT RAZILAŞDIRMA PROTOKOLU &numero; <span class="invoiceNo"></span></h3>
                <h6 class="mb-2 text-center"><span class="companyName"></span> və <span class="clientName"></span> arasında bağlanan <span class="contractDate"></span> tarixli</h6>
                <h6 class="mb-2 text-center">&numero; <span class="contractNo"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Müqaviləyə əlavə</h6>
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

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">CƏMİ</td>
                        <td class="tabelBorder" id="sum2"></td>
                    </tr>
                    <tr  id="vatColumn2">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder" id="vat2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder" id="total2"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <p>Yekun Məbləğ:  <span id="total7">0</span>  AZN (<span class="numberWord"></span>)</p>
                <br><br>
                <div class="float-left text-center">
                    <h6 id="temsilci">Gömrük Təmsilçisi</h6>
                    <br>
                    <p class="companyName"></p>
                    <p>VÖEN: <span class="voen"></span></p>
                    <div class="rekvizit">

                    <p>H/H: <span class="hh"></span></p>
                    <p>M/H <span class="mh"></span></p>
                    <p>KOD: <span class="kod"></span></p>
                    <p>BANK: <span class="bank"></span></p>
                    <p>BANK VOEN: <span class="bvoen"></span></p>
                    <p>S.W.I.F.T: <span class="swift"></span></p>
                    </div>
                    <br>
                    <p>Direktor: <span id="who"></span></p>
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
                    <p class="clientName"></p>
                    <p>VÖEN: <span class="clientVoen"></span></p>
                    <div class="rekvizit">

                    <p>H/H: <span class="clienthh"></span></p>
                    <p>M/H <span class="clientmh"></span></p>
                    <p>KOD: <span class="clientCode"></span></p>
                    <p>BANK: <span class="clientBank"></span></p>
                    <p>BANK VOEN: <span class="clientBvoen"></span></p>
                    <p>S.W.I.F.T: <span class="clientSwift"></span></p>
                    </div>
                    <br>
                    <p class="clientWho"></p>
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
                <p class="float-right invoiceDate"></p>
                <br>
                <br>
                <br>
                <h6 class=" mb-2 text-center"><span class="companyName"></span> və <span class="clientName"></span> arasında bağlanan &numero; <span class="contractNo"></span> </h6>
                <h6 class=" mb-2 text-center">müqaviləyə əsasən göstərilən xidmətlərin Təhvil-Təslim aktı &numero; <span class="invoiceNo"></span></h6>
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

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">CƏMİ</td>
                        <td class="tabelBorder" id="sum3"></td>
                    </tr>
                    <tr id="vatColumn3">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">ƏDV 18%</td>
                        <td class="tabelBorder" id="vat3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="tabelBorder">YEKUN</td>
                        <td class="tabelBorder" id="total3"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <p>Yekun Məbləğ: <span id="total6">0</span> AZN (<span class="numberWord"></span>)</p>
                <br><br>
                <div class="float-left text-center">
                    <h6>Təhvil Verdi:</h6>
                    <br>
                    <br>
                    <p class="text-center companyName"></p>
                    <br>
                    <br>
                    <br>
                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
                <div class="float-right text-center">
                    <h6>Təhvil aldı:</h6>
                    <br>
                    <br>
                    <p class="clientName"></p>
                    <br><br><br>

                    <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            invoiceDate()
            protocolDate()

            var urlParams = new URLSearchParams(window.location.search);
            var company = urlParams.get('company');
            var client = urlParams.get('client');
            var invoiceNoUrl = urlParams.get('invoiceNoUrl');
            var invoiceDateUrl = urlParams.get('invoiceDateUrl');
            var methodUrl = urlParams.get('methodUrl');
            var contractNoUrl = urlParams.get('contractNoUrl');
            var protocolUrl = urlParams.get('protocolUrl');
            var contractDateUrl = urlParams.get('contractDateUrl');
            var savedRows = [];

            if (company) {
                $('#companies').val(company);
            }

            changeCompany();

            $.ajax({
                url: '/module/getClients',
                method: 'GET',
                success: function(response) {
                    const clients = response;
                    const selectElement = $('#clientSelect');
                    const clientNameInput = $('#clientNameInput');
                    const clientVoenInput = $('#clientVoenInput');
                    const clienthhInput = $('#clienthhInput');
                    const clientmhInput = $('#clientmhInput');
                    const clientCodeInput = $('#clientCodeInput');
                    const clientBankInput = $('#clientBankInput');
                    const clientBvoenInput = $('#clientBvoenInput');
                    const clientSwiftInput = $('#clientSwiftInput');
                    const clientWhoInput = $('#clientWhoInput');

                    clients.forEach(function(client) {
                        const optionElement = $('<option>').val(client.id).text(client.name);
                        selectElement.append(optionElement);
                    });

                    const selectedClientId = client;

                    selectElement.find('option').each(function() {
                        if ($(this).val() === selectedClientId) {
                            $(this).prop('selected', true);
                            selectInitialClient();
                            return false;
                        }
                    });

                    function selectInitialClient() {
                        const selectedClientId = selectElement.val();
                        const selectedClient = clients.find(function(client) {
                            return client.id.toString() === selectedClientId;
                        });

                        if (selectedClient) {
                            clientNameInput.val(selectedClient.name);
                            clientVoenInput.val(selectedClient.voen);
                            clienthhInput.val(selectedClient.hn);
                            clientmhInput.val(selectedClient.mh);
                            clientCodeInput.val(selectedClient.code);
                            clientBankInput.val(selectedClient.bank);
                            clientBvoenInput.val(selectedClient.bvoen);
                            clientSwiftInput.val(selectedClient.swift);
                            clientWhoInput.val(selectedClient.orderer);
                            clientName();
                            clientVoen();
                            clienthh();
                            clientmh();
                            clientCode();
                            clientBank();
                            clientBvoen();
                            clientSwift();
                            clientWho();
                        }
                    }

                    selectElement.on('change', function() {
                        selectInitialClient();
                    });

                    selectInitialClient();
                },
                error: function(error) {
                    console.log(error);
                }
            });

        });
        function createClient() {
            $.ajax({
                url: '/module/createFinanceClient',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    name: $('#clientNameInput').val(),
                    voen: $('#clientVoenInput').val(),
                    hn: $('#clienthhInput').val(),
                    mh: $('#clientmhInput').val(),
                    code: $('#clientCodeInput').val(),
                    bank: $('#clientBankInput').val(),
                    bvoen: $('#clientBvoenInput').val(),
                    swift: $('#clientSwiftInput').val(),
                    orderer: $('#clientWhoInput').val()
                },
                success: function(response) {
                    console.log('Müşteri yaratıldı:', response);
                    $('#createClient').hide()
                },
                error: function(error) {
                    console.log('Müşteri yaratılırken hata oluştu:', error);
                }
            });
        }

        $(document).ready(function() {
            $('#loginput').css('display', 'none');
        });

        function changeCompany() {
            var company = $('#companies').val();

            if (company == 'mbrokerKapital') {
                var companyName = "\"Mobil Broker\" MMC";
                var voen = "1804705371";
                var hh = "AZ78AIIB400500D9447193478229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Vüsal Xəlilov İbrahim oğlu";
                var whoFooter = "V.İ.Xəlilov";
            } else if (company == 'mbrokerRespublika') {
                var companyName = "\"Mobil Broker\" MMC";
                var voen = "1804705371";
                var hh = "AZ17BRES00380394401114863601";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Vüsal Xəlilov İbrahim oğlu";
                var whoFooter = "V.İ.Xəlilov";
            } else if (company == 'mgroupRespublika') {
                var companyName = "\"Mobil Group\" MMC";
                var voen = "1405261701";
                var hh = "AZ31BRES00380394401115941601";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Vüsal Xəlilov İbrahim oğlu";
                var whoFooter = "V.İ.Xəlilov";
            } else if (company == 'garantKapital') {
                var companyName = "\"Garant Broker\" MMC";
                var voen = "1803974481";
                var hh = "AZ56AIIB400500D9447227965229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Alişan Cəlilov Maqsud oğlu";
                var whoFooter = "A.M.Cəlilov";
            } else if (company == 'garantRespublika') {
                var companyName = "\"Garant Broker\" MMC";
                var voen = "1803974481";
                var hh = "AZ95BRES00380394401114875001";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Alişan Cəlilov Maqsud oğlu";
                var whoFooter = "A.M.Cəlilov";
            } else if (company == 'rigelKapital') {
                var companyName = "Rigel Group";
                var voen = "1805978211";
                var hh = "AZ61AIIB400500E9445911817229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Xəlilova Lamiyə Fərhad qızı";
                var whoFooter = "L.İ.Xəlilova";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            } else if (company == 'tgroupKapital') {
                var companyName = "\"TEDORA GROUP\" MMC";
                var voen = "1008142601";
                var hh = "AZ61AIIB400500F9443614259229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "Kapital Bank ASC KOB mərkəzi filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Toğrul Surxayzadə Məhərrəm oğlu";
                var whoFooter = "T.M.Surxayzadə";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            }else if (company == 'dgroupKapital') {
                var companyName = "\"DECLARE GROUP\" MMC";
                var voen = "1406438851";
                var hh = "AZ61AIIB400500F9443405268229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "Kapital Bank ASC KOB mərkəzi filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Mahir Həsənquliyev Tahir oğlu";
                var whoFooter = "M.T.Həsənquliyev";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            }else if (company == 'rigelRespublika') {
                var companyName = "Rigel Group";
                var voen = "1805978211";
                var hh = "AZ43BRES00380394401162048201";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Xəlilova Lamiyə Fərhad qızı";
                var whoFooter = "L.İ.Xəlilova";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            } else if (company == 'mindRespublika') {
                var companyName = "\"Mind Services\" MMC";
                var voen = "1506046601";
                var hh = "AZ88BRES00380394401162079401";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Əliyev Fuad Rasim oğlu";
                var whoFooter = "F.R.Əliyev";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            }  else if (company == 'mindKapital') {
                var companyName = "\"Mind Services\" MMC";
                var voen = "1506046601";
                var hh = "AZ28AIIB400500E9444984575229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "Kapital Bank ASC KOB mərkəzi filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Əliyev Fuad Rasim oğlu";
                var whoFooter = "F.R.Əliyev";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            } else if (company == 'asazaRespublika') {
                var companyName = "\"ASAZA FLKS\" MMC";
                var voen = "1805091391";
                var hh = "AZ80BRES00380394401196199101";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Fərhad İbrahimli Əli oğlu";
                var whoFooter = "F.Ə.İbrahimli";
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            } else if (company == 'mtechnologiesRespublika') {
                var companyName = "\"Mobil Technologies\" MMC";
                var voen = "1804325861";
                var hh = "AZ20BRES00380394401131856201";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Sabir Tahirov Zakir oğlu";
                var whoFooter = "S.Z.Tahirov";
                $('#temsilci').html("İcraçı");
            } else if (company == 'logisticsKapital') {
                var companyName = "\"Mobil Logistics\" MMC";
                var voen = "1804811521";
                var hh = "AZ85AIIB400500D9447161910229";
                var mh = "AZ37NABZ01350100000000001944";
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı";
                var kod = "201412";
                var bvoen = "9900003611";
                var swift = "AIIBAZ2XXXX";
                var who = "Xəlilova Lamiyə Fərhad qızı";
                var whoFooter = "L.İ.Xəlilova";
                $('#temsilci').html("Ekspeditor");
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            }else if (company == 'logisticsRespublika') {
                var companyName = "\"Mobil Logistics\" MMC";
                var voen = "1804811521";
                var hh = "AZ77BRES00380394401116001301";
                var mh = "AZ80NABZ01350100000000014944";
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı";
                var kod = "507547";
                var bvoen = "9900001901";
                var swift = "BRESAZ22";
                var who = "Xəlilova Lamiyə Fərhad qızı";
                var whoFooter = "L.İ.Xəlilova";
                $('#temsilci').html("Ekspeditor");
                $('#vatColumn, #vatColumn2, #vatColumn3').hide();
            }

            $('.companyName').text(companyName);
            $('.voen').text(voen);
            $('.hh').text(hh);
            $('.mh').text(mh);
            $('.bank').text(bank);
            $('.kod').text(kod);
            $('.bvoen').text(bvoen);
            $('.swift').text(swift);

            $('#who').text(who);
            $('#who-footer').text(whoFooter);

            if (company == 'logisticsKapital' || company == 'logisticsRespublika') {
                $('#loginput').show();
                $('#brokerinput').hide();
            } else {
                $('#loginput').hide();
                $('#brokerinput').show();
            }
        }

        function clientName() {
            var clientNameInput = $("#clientNameInput").val();
            $(".clientName").html(clientNameInput);
        }
        function invoiceNumbers() {
            var invoiceNumbersInput = $("#invoiceNumbersInput").val();
            $(".invoiceNumbers").html(invoiceNumbersInput);
        }

        function clientVoen() {
            var clientVoenInput = $("#clientVoenInput").val();
            $(".clientVoen").html(clientVoenInput);
        }

        function clienthh() {
            var clienthhInput = $("#clienthhInput").val();
            $(".clienthh").html(clienthhInput);
        }

        function clientmh() {
            var clientmhInput = $("#clientmhInput").val();
            $(".clientmh").html(clientmhInput);
        }

        function clientCode() {
            var clientCodeInput = $("#clientCodeInput").val();
            $(".clientCode").html(clientCodeInput);
        }

        function clientBank() {
            var clientBankInput = $("#clientBankInput").val();
            $(".clientBank").html(clientBankInput);
        }

        function clientBvoen() {
            var clientBvoenInput = $("#clientBvoenInput").val();
            $(".clientBvoen").html(clientBvoenInput);
        }

        function clientSwift() {
            var clientSwiftInput = $("#clientSwiftInput").val();
            $(".clientSwift").html(clientSwiftInput);
        }

        function clientWho() {
            var clientWhoInput = $("#clientWhoInput").val();
            $(".clientWho").html(clientWhoInput);
        }

        function invoiceNo() {
            var invoiceNoInput = $("#invoiceNoInput").val();
            $(".invoiceNo").html(invoiceNoInput);
        }

        function invoiceDate() {
            var invoiceDateInput = $("#invoiceDateInput").val();
            $(".invoiceDate").html(invoiceDateInput);
        }

        function paymentType() {
            var paymentTypeSelect = $("#paymentTypeSelect").val();
            $("#paymentType").html(paymentTypeSelect);
            if (paymentTypeSelect == 'nağd' ) {
                $('.rekvizit').hide()
            }
        }

        function protocolDate() {
            var protocolDateInput = $("#protocolDateInput").val();
            $(".protocolDate").html(protocolDateInput);
        }

        function contractNo() {
            var contractNoInput = $("#contractNoInput").val();
            $(".contractNo").html(contractNoInput);
        }

        function contractDate() {
            var contractDateInput = $("#contractDateInput").val();
            $(".contractDate").html(contractDateInput);
        }

        function calculateTotal() {
            var miktarHucres = $('.miktar-hucre');
            var sum = 0;

            miktarHucres.each(function() {
                var miktarHucre = $(this);
                var value = parseFloat(miktarHucre.text());
                if (!isNaN(value)) {
                    sum += value;
                }
            });

            var sumCell = $('#sum');
            var vatCell = $('#vat');
            var totalCell = $('#total');

            var edvCompany = $('#companies').val();
            var edv = (edvCompany !== 'mbrokerRespublika' && edvCompany !== 'mtechnologiesRespublika' && edvCompany !== 'garantRespublika' && edvCompany !== 'garantKapital' && edvCompany !== 'mbrokerKapital') ? 1 : 1.18;

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
        }
        var savedRows = [];
        function addRow() {
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
            var cells = row.cells;
            for (var i = 0; i < cells.length; i++) {
                var cell = cells[i];
                cell.setAttribute('contenteditable', 'true');
            }
        }

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
            savedRows.splice(rowIndex - 1, 1);

            calculateTotal();
        }

        function createInvoice() {
            $.ajax({
                url: '/module/createFinanceInvoice',
                type: 'POST',
                data: {
                    company: $('#companies').val(),
                    client: $('#clientSelect').val(),
                    invoiceNo: $('#invoiceNoInput').val(),
                    invoiceDate: $("#invoiceDateInput").val(),
                    paymentType: $('#paymentTypeSelect').val(),
                    protocolDate: $('#protocolDateInput').val(),
                    contractNo: $('#contractNoInput').val(),
                    contractDate: $('#contractDateInput').val(),
                    invoiceNumbers: $('#invoiceNumbersInput').val(),
                    services: savedRows
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
            $('#print-area').hide();

            var printContent = $('#printCard2').html();
            var originalContent = $('body').html();

            $('body').html(printContent);
            window.print();
            $('body').html(originalContent);
        }

        function printCard3() {
            $('#print-area').hide();

            var printContent = $('#printCard3').html();
            var originalContent = $('body').html();

            $('body').html(printContent);
            window.print();
            $('body').html(originalContent);
        }
    </script>
    <script>
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

            var combinedContent = printContent1 + '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>' + printContent2 + '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>' + printContent3;
            $('body').html(combinedContent);
            window.print();
            $('body').html(originalContent);
        }
    </script>
@endsection