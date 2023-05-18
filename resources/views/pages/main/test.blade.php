@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')

    <div class="container">
        <button onclick="printCard()" class="btn btn-primary">Print</button>

        <div class="card" id="print-card">
            <div class="card-body">
                <h2 class=" text-center">"Mobil Broker" MMC</h2>
                <h3 class="card mb-2 text-center">HESAB FAKTURA &numero; 409</h3>
                <h6 class="card mb-2">"Mobil Broker" MMC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VOEN: 1804705371</h6>
                <h6 class="card mb-2">H/H: AZ78AIIB400500D9447193478229</h6>
                <h6 class="card mb-2">M/H AZ37NABZ01350100000000001944</h6>
                <h6 class="card mb-2">BANK: KAPITAL BANK ASC KOB mərkəz filialı KOD:201412</h6>
                <h6 class="card mb-2">BANK: VOEN: 9900003611 &nbsp;&nbsp;  S.W.I.F.T: AIIBAZ2XXXX</h6>

                <h1 class="text-center">Mobil Broker</h1>
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
                        <td colspan="2" class="border border-bottom-0">"Bakı Abadlıq Xidməti" MMC</td>
                        <td class="border">Tarix: 16.05.223</td>
                        <td class="border">No 409</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-top-0">VÖEN: 1301709881</td>
                        <td class="border">Ödəmə növü: köçürmə</td>
                        <td class="border">1,510.40</td>
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
                        <td colspan="5">Yekun Məbləğ: 59 AZN (ƏLLİ DOQQUZ MANAT)</td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <br>

            <p class="float-left">"Mobil Broker" MMC-nin direktoru</p>
            <p class="float-right">V.İ.XƏLİLOV</p>
            </div>

        </div>
    </div>@endsection


@section('scripts')
    <script>
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

            var vat =  vatCell.textContent = sum.toFixed(2) * 0.18;
            totalCell.textContent = sum.toFixed(2) + vat;
        }
        function addRow() {
            var tableBody = document.getElementById('table-body');
            var newRow = tableBody.insertRow(0);
            var selectElement = document.getElementById('input1');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var input1 = selectedOption.innerHTML;
            var input3 = document.getElementById('input3').value;
            var input4 = document.getElementById('input4').value;

            var cell1 = newRow.insertCell(0);
            cell1.textContent = input1;

            var cell2 = newRow.insertCell(1);
            cell2.textContent = "Ədəd";

            var cell3 = newRow.insertCell(2);
            cell3.textContent = input3;

            var cell4 = newRow.insertCell(3);
            cell4.textContent = input4;

            var cell5 = newRow.insertCell(4);
            cell5.textContent = input3 * input4;
            cell5.classList.add('miktar-hucre');

            var cell6 = newRow.insertCell(5);
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
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
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