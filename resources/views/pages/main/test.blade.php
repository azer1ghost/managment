@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="row col-12 justify-content-center">
                        <select class="form-control col-3 m-2" id="companies" onchange="changeCompany()">
                            <option value="">Şirkət Seçin</option>
                            <option value="mbrokerKapital">Mobil Broker Kapital</option>
                            <option value="mbrokerRespublika">Mobil Broker Bank Respublika</option>
                            <option value="garantKapital">Garant Broker Kapital</option>
                            <option value="garantRespublika">Garant Broker Bank Respublika</option>
                            <option value="rigelRespublika">Rigel Bank Respublika</option>
                            <option value="rigelKapital">Rigel Kapital</option>
                            <option value="mindRespublika">Mind Bank Respublika</option>
                            <option value="asazaRespublika">Asaza Bank Respublika</option>
                            <option value="logisticsKapital">Mobil Logistics Kapital</option>
                        </select>
                    </div>
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
                    </div>


{{--                            <h3>Müştəri Məlumatları</h3>--}}
                        <input class="form-control col-3 m-2" id="invoiceNoInput" placeholder="Hesab faktura nömrəsi"
                               oninput="invoiceNo()">
                        <input class="form-control col-3 m-2" id="invoiceDateInput" placeholder="Hesab faktura tarixi"
                               oninput="invoiceDate()">

                        <select class="form-control col-3 m-2" id="paymentTypeSelect" onchange="paymentType()">
                            <option value="">Ödəmə növü</option>
                            <option value="kocurme">Köçürmə</option>
                            <option value="nağd">Nağd</option>
                        </select>


                        <input class="form-control col-3 m-2" id="protocolNoInput"
                               placeholder="Qiymət Razılaşdırma Protokolu nömrəsi" oninput="protocolNo()">
                        <input class="form-control col-3 m-2" id="protocolDateInput"
                               placeholder="Protokol tarixi" oninput="protocolDate()">
                        <input class="form-control col-3 m-2" id="contractNoInput" placeholder="Müqavilə nömrəsi"
                               oninput="contractNo()">
                        <input class="form-control col-3 m-2" id="contractDateInput" placeholder="Müqavilə tarixi"
                               oninput="contractDate()">



                        <input class="form-control col-3 m-2" id="statementDateInput"
                               placeholder="Təhvil Təslim Aktı Nömrəsi" oninput="statementDate()">


                </div>
            </div>

        </div>
    </div>

    <br><br>

    <div class="container">
        <button onclick="printCard()" class="btn btn-primary float-right">Print</button>
        <div class="card" id="print-card">
            <div class="card-body">
                <h2 class="text-center companyName" id="companyName"></h2>
                <h3 class="  text-center">HESAB FAKTURA &numero; 409</h3>
                <h6 class=" mb-2"><span class="companyName"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VOEN: <span class="voen"></span></h6>
                <h6 class=" mb-2">H/H: <span class="hh"></span></h6>
                <h6 class=" mb-2">M/H <span class="mh"></span></h6>
                <h6 class=" mb-2">BANK: <span class="bank"></span> KOD: <span class="kod"></span></h6>
                <h6 class=" mb-2">BANK VOEN: <span class="bbank"></span> &nbsp;&nbsp;  S.W.I.F.T: <span class="swift"></span></h6>

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
                        <td colspan="2" class="border border-bottom-0 clientName"></td>
                        <td class="border">Tarix: <span class="invoiceDate"></span></td>
                        <td class="border">&numero; <span class="invoiceNo"></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0">VÖEN: <span class="clientVoen"></span></td>
                        <td class="border">Ödəmə növü: <span id="paymentType"></span></td>
                        <td class="border" id="total4"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Əmtəənin Adı</th>
                        <th>Ölçü Vahidi</th>
                        <th>Miqdarı</th>
                        <th>Qiymət</th>
                        <th>Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    <tr id="form-area">
                        <td>
                            <select id="input1" class="form-control">
                                <option>Elektron GB-in tərtib olunması</option>
                                <option>Elektron GB-in tərtib olunması 2</option>
                                <option>Elektron GB-in tərtib olunması 3</option>
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
                        <td>CƏMİ</td>
                        <td id="sum"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ƏDV 18%</td>
                        <td id="vat"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>YEKUN</td>
                        <td id="total"></td>
                    </tr>
                    <tr>
                        <td colspan="5" id="total-text">Yekun Məbləğ: 59 AZN (ƏLLİ DOQQUZ MANAT)</td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>

            <p class="float-left"><span class="companyName"></span>-nin direktoru</p>
            <p class="float-right" id="who-footer"></p>
            </div>

        </div>
    </div>
    <div class="container">
        <br>
        <div class="card" id="print-card">
            <div class="card-body">
                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right">15.03.2023</p>
                <br>
                <br>
                <br>
                <h3 class="card mb-2 text-center">QİYMƏT RAZILAŞDIRMA PROTOKOLU &numero; 10/H</h3>
                <h6 class="card mb-2 text-center"><span class="companyName"></span> və "BAKI NƏQLİYYAT AGENTLİYİ" Publik Hüquqi Şəxs arasında bağlanan 05 Yanvar 2022-ci il tarixli</h6>
                <h6 class="card mb-2 text-center">&numero; 01-1/03/22 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Müqaviləyə əlavə</h6>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sıra &numero;</th>
                        <th>İş və Xidmətin Adı</th>
                        <th>Ölçü Vahidi</th>
                        <th>Say</th>
                        <th>Vahidin Qiyməti</th>
                        <th>Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body2">

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>CƏMİ</td>
                        <td id="sum2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ƏDV 18%</td>
                        <td id="vat2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>YEKUN</td>
                        <td id="total2"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <p>Yekun Məbləğ: 59 AZN (ƏLLİ DOQQUZ MANAT)</p>
                <br><br>
            <div class="float-left text-center">
                <h6 id="who"></h6>
                <br>
                <p class="companyName"></p>
                <p>VÖEN: <span class="voen"></span></p>
                <p>H/H: <span class="hh"></span></p>
                <p>M/H <span class="mh"></span></p>
                <p>KOD: <span class="kod"></span></p>
                <p>BANK: <span class="bank"></span></p>
                <p>BANK VOEN: <span class="bvoen"></span></p>
                <p>S.W.I.F.T: <span class="swift"></span></p>
                <br>
                <p>Director: <span class="director"></span></p>
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
                <p>H/H: <span class="clienthh"></span></p>
                <p>M/H <span class="clientmh"></span></p>
                <p>KOD: <span class="clientCode"></span></p>
                <p>BANK: <span class="clientBank"></span></p>
                <p>BANK VOEN: <span class="clientBvoen"></span></p>
                <p>S.W.I.F.T: <span class="clientSwift"></span></p>
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
        <div class="card" id="print-card">
            <div class="card-body">
                <p class="float-left">Bakı Şəhəri</p>
                <p class="float-right">15.03.2023</p>
                <br>
                <br>
                <br>
                <h6 class="card mb-2 text-center"><span class="companyName"></span> və "BAKI NƏQLİYYAT AGENTLİYİ" Publik Hüquqi Şəxs arasında bağlanan &numero; 01-1/03/22  tarixli</h6>
                <h6 class="card mb-2 text-center">müqaviləyə əsasən göstərilən xidmətlərin Təhvil-Təslim aktı &numero; 10/H</h6>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sıra &numero;</th>
                        <th>İş və Xidmətin Adı</th>
                        <th>Ölçü Vahidi</th>
                        <th>Say</th>
                        <th>Vahidin Qiyməti</th>
                        <th>Məbləğ</th>
                    </tr>
                    </thead>
                    <tbody id="table-body3">

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>CƏMİ</td>
                        <td id="sum3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ƏDV 18%</td>
                        <td id="vat3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>YEKUN</td>
                        <td id="total3"></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <p>Yekun Məbləğ: 59 AZN (ƏLLİ DOQQUZ MANAT)</p>
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
                <p>"BAKI NƏQLİYYAT AGENTLİYİ" Publik Hüquqi Şəxs</p>
                <br><br><br>

                <p class="border-bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

            </div>
            </div>

        </div>
    </div>


@endsection


@section('scripts')
    <script>

        function changeCompany() {

         
            var company = document.getElementById('companies').value;

            if (company == 'mbrokerKapital') {
                var companyName = "\"Mobil Broker\" MMC"
                var voen = "1804705371"
                var hh = "AZ78AIIB400500D9447193478229"
                var mh = "AZ37NABZ01350100000000001944"
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı"
                var kod = "201412"
                var bvoen = "9900003611"
                var swift = "AIIBAZ2XXXX"
                var who = "Vüsal Xəlilov İbrahim oğlu"
                var whoFooter = "V.İ.Xəlolov"
            }else if (company == 'mbrokerRespublika'){
                var companyName = "\"Mobil Broker\" MMC"
                var voen = "1804705371"
                var hh = "AZ17BRES00380394401114863601"
                var mh = "AZ80NABZ01350100000000014944"
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı"
                var kod = "507547"
                var bvoen = "9900001901"
                var swift = "BRESAZ22"
                var who = "Vüsal Xəlilov İbrahim oğlu"
                var whoFooter = "V.İ.Xəlolov"
            }else if (company == 'garantKapital'){
                var companyName = "\"Garant Broker\" MMC"
                var voen = "1803974481"
                var hh = "AZ56AIIB400500D9447227965229"
                var mh = "AZ37NABZ01350100000000001944"
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı"
                var kod = "201412"
                var bvoen = "9900003611"
                var swift = "AIIBAZ2XXXX"
                var who = "Alişan Cəlilov Maqsud oğlu"
                var whoFooter = "A.M.Cəlilov"
            }else if (company == 'garantRespublika'){
                var companyName = "\"Garant Broker\" MMC"
                var voen = "1803974481"
                var hh = "AZ95BRES00380394401114875001"
                var mh = "AZ80NABZ01350100000000014944"
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı"
                var kod = "201412"
                var bvoen = "9900001901"
                var swift = "BRESAZ22"
                var who = "Alişan Cəlilov Maqsud oğlu"
                var whoFooter = "A.M.Cəlilov"
            }else if (company == 'rigelKapital'){
                var companyName = "Rigel Group"
                var voen = "1805978211"
                var hh = "AZ61AIIB400500E9445911817229"
                var mh = "AZ37NABZ01350100000000001944"
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı"
                var kod = "201412"
                var bvoen = "9900003611"
                var swift = "AIIBAZ2XXXX"
                var who = "Xəlilova Lamiyyə İbrahim qızı"
                var whoFooter = "L.İ.Xəlilova"
            }else if (company == 'rigelRespublika'){
                var companyName = "Rigel Group"
                var voen = "1805978211"
                var hh = "AZ43BRES00380394401162048201"
                var mh = "AZ80NABZ01350100000000014944"
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı"
                var kod = "507547"
                var bvoen = "9900001901"
                var swift = "BRESAZ22"
                var who = "Xəlilova Lamiyyə İbrahim qızı"
                var whoFooter = "L.İ.Xəlilova"
            }else if (company == 'mindRespublika'){
                var companyName = "\"Mind Services\" MMC"
                var voen = "1506046601"
                var hh = "AZ88BRES00380394401162079401"
                var mh = "AZ80NABZ01350100000000014944"
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı"
                var kod = "507547"
                var bvoen = "9900001901"
                var swift = "BRESAZ22"
                var who = "Əliyev Fuad Rasim oğlu"
                var whoFooter = "F.R.Əliyev"
            }else if (company == 'asazaRespublika'){
                var companyName = "\"ASAZA FLKS\" MMC"
                var voen = "1805091391"
                var hh = "AZ80BRES00380394401196199101"
                var mh = "AZ80NABZ01350100000000014944"
                var bank = "Bank Respublika ASC-nin 'Azadlıq' filialı"
                var kod = "507547"
                var bvoen = "9900001901"
                var swift = "BRESAZ22"
                var who = "Fərhad İbrahimli Əli oğlu"
                var whoFooter = "F.Ə.İbrahimli"
            }else if (company == 'logisticsKapital'){
                var companyName = "\"Mobil Logistics\" MMC"
                var voen = "1804811521"
                var hh = "AZ85AIIB400500D9447161910229"
                var mh = "AZ37NABZ01350100000000001944"
                var bank = "KAPITAL BANK ASC KOB mərkəz filialı"
                var kod = "201412"
                var bvoen = "9900003611"
                var swift = "AIIBAZ2XXXX"
                var who = "Xəlilova Lamiyyə İbrahim qızı"
                var whoFooter = "L.İ.Xəlilova"
            }

            var companyNameAdd = document.getElementsByClassName("companyName");
            var voenAdd = document.getElementsByClassName("voen");
            var hhAdd = document.getElementsByClassName("hh");
            var mhAdd = document.getElementsByClassName("mh");
            var bankAdd = document.getElementsByClassName("bank");
            var kodAdd = document.getElementsByClassName("kod");
            var bvoenAdd = document.getElementsByClassName("bvoen");
            var swiftAdd = document.getElementsByClassName("swift");

           document.getElementById("who").textContent = who;
           // document.getElementById("who-footer").textContent = whoFooter;
            for (var i = 0; i < companyNameAdd.length; i++) {
                companyNameAdd[i].textContent = companyName
            }
            for (var i = 0; i < voenAdd.length; i++) {
                voenAdd[i].textContent = voen
            }
            for (var i = 0; i < hhAdd.length; i++) {
                hhAdd[i].textContent = hh
            }
            for (var i = 0; i < mhAdd.length; i++) {
                mhAdd[i].textContent = mh
            }
            for (var i = 0; i < kodAdd.length; i++) {
                kodAdd[i].textContent = kod
            }
            for (var i = 0; i < bankAdd.length; i++) {
                bankAdd[i].textContent = bank
            }
            for (var i = 0; i < bvoenAdd.length; i++) {
                bvoenAdd[i].textContent = bvoen
            }
            for (var i = 0; i < swiftAdd.length; i++) {
                swiftAdd[i].textContent = swift
            }
        }

// client recvisit


        function clientName() {
            var clientNameInput = document.getElementById("clientNameInput").value;
            var clientName = document.getElementsByClassName("clientName");
            for (var i = 0; i < clientName.length; i++) {
                clientName[i].innerHTML = clientNameInput;
            }
        }

        function clientVoen() {
            var clientVoenInput = document.getElementById("clientVoenInput").value;
            var clientVoen = document.getElementsByClassName("clientVoen");
            for (var i = 0; i < clientVoen.length; i++) {
                clientVoen[i].innerHTML = clientVoenInput;
            }
        }

        function clienthh() {
            var clienthhInput = document.getElementById("clienthhInput").value;
            var clienthh = document.getElementsByClassName("clienthh");
            for (var i = 0; i < clienthh.length; i++) {
                clienthh[i].innerHTML = clienthhInput;
            }
        }

        function clientmh() {
            var clientmhInput = document.getElementById("clientmhInput").value;
            var clientmh = document.getElementsByClassName("clientmh");
            for (var i = 0; i < clientmh.length; i++) {
                clientmh[i].innerHTML = clientmhInput;
            }
        }

        function clientCode() {
            var clientCodeInput = document.getElementById("clientCodeInput").value;
            var clientCode = document.getElementsByClassName("clientCode");
            for (var i = 0; i < clientCode.length; i++) {
                clientCode[i].innerHTML = clientCodeInput;
            }
        }

        function clientBank() {
            var clientBankInput = document.getElementById("clientBankInput").value;
            var clientBank = document.getElementsByClassName("clientBank");
            for (var i = 0; i < clientBank.length; i++) {
                clientBank[i].innerHTML = clientBankInput;
            }
        }

        function clientBvoen() {
            var clientBvoenInput = document.getElementById("clientBvoenInput").value;
            var clientBvoen = document.getElementsByClassName("clientBvoen");
            for (var i = 0; i < clientBvoen.length; i++) {
                clientBvoen[i].innerHTML = clientBvoenInput;
            }
        }

        function clientSwift() {
            var clientSwiftInput = document.getElementById("clientSwiftInput").value;
            var clientSwift = document.getElementsByClassName("clientSwift");
            for (var i = 0; i < clientSwift.length; i++) {
                clientSwift[i].innerHTML = clientSwiftInput;
            }
        }
        function clientWho() {
            var clientWhoInput = document.getElementById("clientWhoInput").value;
            var clientWho = document.getElementsByClassName("clientWho");
            for (var i = 0; i < clientWho.length; i++) {
                clientWho[i].innerHTML = clientWhoInput;
            }
        }
        function clientWho() {
            var clientWhoInput = document.getElementById("clientWhoInput").value;
            var clientWho = document.getElementsByClassName("clientWho");
            for (var i = 0; i < clientWho.length; i++) {
                clientWho[i].innerHTML = clientWhoInput;
            }
        }
        function invoiceNo() {
            var invoiceNoInput = document.getElementById("invoiceNoInput").value;
            var invoiceNo = document.getElementsByClassName("invoiceNo");
            for (var i = 0; i < invoiceNo.length; i++) {
                invoiceNo[i].innerHTML = invoiceNoInput;
            }
        }
        function invoiceDate() {
            var invoiceDateInput = document.getElementById("invoiceDateInput").value;
            var invoiceDate = document.getElementsByClassName("invoiceDate");
            for (var i = 0; i < invoiceDate.length; i++) {
                invoiceDate[i].innerHTML = invoiceDateInput;
            }
        }
        function paymentType() {
            var paymentTypeSelect = document.getElementById("paymentTypeSelect").value;
            document.getElementById("paymentType").innerHTML = paymentTypeSelect;
        }
        function protocolNo() {
            var protocolNoInput = document.getElementById("protocolNoInput").value;
            var protocolNo = document.getElementsByClassName("protocolNo");
            for (var i = 0; i < protocolNo.length; i++) {
                protocolNo[i].innerHTML = protocolNoInput;
            }
        }

        function protocolDate() {
            var protocolDateInput = document.getElementById("protocolDateInput").value;
            var protocolDate = document.getElementsByClassName("protocolDate");
            for (var i = 0; i < protocolDate.length; i++) {
                protocolDate[i].innerHTML = protocolDateInput;
            }
        }

        function contractNo() {
            var contractNoInput = document.getElementById("contractNoInput").value;
            var contractNo = document.getElementsByClassName("contractNo");
            for (var i = 0; i < contractNo.length; i++) {
                contractNo[i].innerHTML = contractNoInput;
            }
        }
        function contractDate() {
            var contractDateInput = document.getElementById("contractDateInput").value;
            var contractDate = document.getElementsByClassName("contractDate");
            for (var i = 0; i < contractDate.length; i++) {
                contractDate[i].innerHTML = contractDateInput;
            }
        }
        function statementDate() {
            var statementDateInput = document.getElementById("statementDateInput").value;
            var statementDate = document.getElementsByClassName("statementDate");
            for (var i = 0; i < statementDate.length; i++) {
                statementDate[i].innerHTML = statementDateInput;
            }
        }

        function calculateTotal() {
            var miktarHucres = document.getElementsByClassName('miktar-hucre');
            var sum = 0;

            for (var i = 0; i < miktarHucres.length; i++) {
                var miktarHucre = miktarHucres[i];
                var value = parseFloat(miktarHucre.textContent);
                if (!isNaN(value)) {
                    sum += value;
                }
            }

            var sumCell = document.getElementById('sum');
            var vatCell = document.getElementById('vat');
            var totalCell = document.getElementById('total');
            sumCell.textContent = sum.toFixed(2);
            vatCell.textContent = sum.toFixed(2) * 0.18;
            totalCell.textContent = sum.toFixed(2) * 1.18;
            document.getElementById('sum2').innerHTML = sum.toFixed(2);
            document.getElementById('vat2').innerHTML = sum.toFixed(2) * 0.18;
            document.getElementById('total2').innerHTML = sum.toFixed(2) * 1.18;
            document.getElementById('sum3').innerHTML = sum.toFixed(2);
            document.getElementById('vat3').innerHTML = sum.toFixed(2) * 0.18;
            document.getElementById('total3').innerHTML = sum.toFixed(2) * 1.18;
            document.getElementById('total4').innerHTML = sum.toFixed(2) * 1.18;
        }
        function addRow() {
            var tableBody = document.getElementById('table-body');
            var newRow = tableBody.insertRow(tableBody.rows.length-5);
            var selectElement = document.getElementById('input1');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var input1 = selectedOption.innerHTML;
            var input3 = document.getElementById('input3').value;
            var input4 = document.getElementById('input4').value;

            var tableBody2 = document.getElementById('table-body2');
            var newRow2 = tableBody2.insertRow(tableBody2.rows.length-3);

            var tableBody3 = document.getElementById('table-body3');
            var newRow3 = tableBody3.insertRow(tableBody3.rows.length-3);

            var cell1 = newRow.insertCell(0);
            var cell12 = newRow2.insertCell(0);
            var cell13 = newRow3.insertCell(0);
            cell12.textContent = newRow2.parentNode.rows.length-3;
            cell13.textContent = newRow3.parentNode.rows.length-3;
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
            cell32.textContent ="Ədəd";
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
            cell62.textContent = input3 * input4;
            cell63.textContent = input3 * input4;
            var deleteButton = document.createElement('button');
            deleteButton.textContent = 'Sil';
            deleteButton.className = 'btn btn-danger';
            deleteButton.onclick = function() {
                deleteRow(this);
            };
            cell6.appendChild(deleteButton);

            document.getElementById('input1').value = '';
            document.getElementById('input3').value = '';
            document.getElementById('input4').value = '';
            calculateTotal();

            console.log(sayiyiYaziyaCevir(document.getElementById('total').innerHTML))
        }

        function sayiyiYaziyaCevir(sayi) {
            var birler = ['sıfır', 'bir', 'iki', 'üç', 'dört', 'beş', 'altı', 'yedi', 'sekiz', 'dokuz'];
            var onlar = ['', 'on', 'yirmi', 'otuz', 'kırk', 'elli', 'altmış', 'yetmiş', 'seksen', 'doksan'];
            var binlikBasamaklar = ['', 'bin', 'milyon', 'milyar', 'trilyon', 'katrilyon'];

            if (sayi === 0) {
                return 'sıfır';
            }

            var yazi = '';
            var negatif = false;

            if (sayi < 0) {
                negatif = true;
                sayi = Math.abs(sayi);
            }

            var gruplar = [];
            while (sayi > 0) {
                gruplar.push(sayi % 1000);
                sayi = Math.floor(sayi / 1000);
            }

            if (gruplar.length === 0) {
                return 'sıfır';
            }

            var grupIndeks = gruplar.length - 1;

            for (var i = grupIndeks; i >= 0; i--) {
                var grup = gruplar[i];

                if (grup === 0) {
                    grupIndeks--;
                    continue;
                }

                var grupYazi = '';

                var yuzlerBasamagi = Math.floor(grup / 100);
                var onlarBasamagi = Math.floor((grup % 100) / 10);
                var birlerBasamagi = grup % 10;

                if (yuzlerBasamagi > 0) {
                    grupYazi += birler[yuzlerBasamagi] + ' yüz ';
                }

                if (onlarBasamagi > 0) {
                    grupYazi += onlar[onlarBasamagi] + ' ';
                }

                if (birlerBasamagi > 0) {
                    grupYazi += birler[birlerBasamagi] + ' ';
                }

                grupYazi += binlikBasamaklar[grupIndeks] + ' ';

                yazi += grupYazi;

                grupIndeks--;
            }

            yazi = yazi.trim();

            if (negatif) {
                yazi = 'eksi ' + yazi;
            }

            return yazi;
        }
        console.log(yazi); // "bin"


        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            var rowIndex = row.rowIndex;

            var tableBody = document.getElementById('table-body');
            var tableBody2 = document.getElementById('table-body2');
            var tableBody3 = document.getElementById('table-body3');

            tableBody.deleteRow(rowIndex - 1);
            tableBody2.deleteRow(rowIndex - 1);
            tableBody3.deleteRow(rowIndex - 1);

            var rows2 = tableBody2.rows;
            var rows3 = tableBody3.rows;

            for (var i = rowIndex - 1; i < rows2.length; i++) {
                var row2 = rows2[i];
                var row3 = rows3[i];

                row2.cells[0].textContent = i + 1;
                row3.cells[0].textContent = i + 1;
            }

            calculateTotal();
        }
        function printCard() {
            document.getElementById('print-area').style.display = 'none';
            document.getElementById('form-area').style.display = 'none';
            var btns = document.getElementsByClassName('btn-danger');
            for (var i = 0; i < btns.length; i++) {
                var parentElement = btns[i].parentNode;
                parentElement.style.display = 'none';}

            var printContent = document.getElementById('print-card').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection