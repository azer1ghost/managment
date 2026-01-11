@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))
@section('style')
    <style>
        #table {
            text-align: center;
            width: 100%;
        }
        .sum-table {
            width: 40%;
            font-size: 18px;

        }

        input {
            width: 40px;
        }

        .table-container {
            max-width: 100%;
            overflow: auto;
        }
    </style>
@endsection
@section('content')

    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.salary')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <form action="{{route('salaries.index')}}" method="GET" class="mb-3">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-md-3">
                <label>İl</label>
                <select class="form-control" name="year" onchange="this.form.submit()">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label>Ay</label>
                @php
                    $months = [
                        '01' => 'Yanvar',
                        '02' => 'Fevral',
                        '03' => 'Mart',
                        '04' => 'Aprel',
                        '05' => 'May',
                        '06' => 'İyun',
                        '07' => 'İyul',
                        '08' => 'Avqust',
                        '09' => 'Sentyabr',
                        '10' => 'Oktyabr',
                        '11' => 'Noyabr',
                        '12' => 'Dekabr',
                    ];
                @endphp
                <select class="form-control" name="month" onchange="this.form.submit()">
                    @foreach($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}" {{ $month == $monthNumber ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Şirkət</label>
                <select class="form-control" name="company_id" onchange="this.form.submit()">
                    <option value="">Hamısı</option>
                    @foreach(\App\Models\Company::get(['id', 'name']) as $company)
                        <option value="{{ $company->id }}" {{ ($company_id && (int)$company_id == $company->id) ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

        <div class="row d-flex justify-content-between mb-2">
                <div class="col-12 float-right mb-2">
                    <a class="btn btn-outline-success float-right"
                       href="{{route('salaries.create')}}">@lang('translates.buttons.create')</a>
                </div>
            <div class="col-12 float-right mb-2">
                <a class="btn btn-outline-success float-right"
                   href="{{route('selectCompany-salaryReport')}}">Hesablanmış Əmək Haqqı</a>
            </div>

            <div class="col-12 table-container">
                <div class="table-responsive" style="overflow-x: auto;">
                <table id="table" class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col" colspan="4">Əməkdaş</th>
                        <th scope="col" colspan="6">Hesablanıb</th>
                        <th scope="col" colspan="4">Tutulmuşdur</th>
                        <th scope="col" colspan="3">İşəgötürən Tərəfindən</th>
                        <th scope="col" colspan="1"></th>
                        <th scope="col" colspan="1"></th>
                        <th scope="col" colspan="1"></th>
                        <th scope="col" colspan="1"><button id="saveButton" class="btn btn-primary">@lang('translates.buttons.save')</button></th>
                    </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('translates.columns.user')</th>
                            <th scope="col">@lang('translates.fields.position')</th>
                            <th scope="col">@lang('translates.columns.salary')</th>
                            <th scope="col">@lang('translates.columns.work_days')</th>
                            <th scope="col">@lang('translates.columns.actual_days')</th>
                            <th scope="col">@lang('translates.columns.calculated_salary')</th>
                            <th scope="col">@lang('translates.columns.prize')</th>
                            <th scope="col">@lang('translates.columns.vacation')</th>
                            <th scope="col">@lang('translates.columns.total')</th>
                            <th scope="col">@lang('translates.columns.salary_tax')</th>
                            <th scope="col">Pensiya fondu</th>
                            <th scope="col">I.S.H</th>
                            <th scope="col">I.T.S.H</th>
                            <th scope="col">Pensiya fondu</th>
                            <th scope="col">I.S.H</th>
                            <th scope="col">I.T.S.H</th>
                            <th scope="col">Cəmi Tutulmuşdur</th>
                            <th scope="col">Ödənilməli məbləğ</th>
                            <th scope="col">@lang('translates.columns.advance')</th>
                            <th scope="col">Yekun ödənilməli məbləğ</th>
                        </tr>
                    </thead>
                    <tbody id="tblNewAttendees">
                    @forelse($salaries as $salary)
                        @php
                          $isReport = $salary instanceof \App\Models\SalaryReport;
                          
                          if ($isReport) {
                              // Əgər SalaryReport-dursa, məlumatları ondan götür
                              $gross = $salary->getAttribute('salary') ?: 0;
                              $workDays = $salary->getAttribute('working_days') ?: 26;
                              $actualDays = $salary->getAttribute('actual_days') ?: 26;
                              $prize = $salary->getAttribute('prize') ?: 0;
                              $vacation = $salary->getAttribute('vacation') ?: 0;
                              $advance = $salary->getAttribute('advance') ?: 0;
                          } else {
                              // Əgər Salary-dirsə, hesablamalar apar
                              $gross = 0;
                              $net = 0;
                              $totalgb = 0;
                              $totalqib = 0;
                              $totalrepresentation = 0;
                              $totalcmr = 0;
                              $totalbranchgb = 0;
                              $totalbranchqib = 0;

                              $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                              $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

                              $works = \App\Models\Work::where('user_id', $salary->getRelationValue('user')->id)
                                ->whereDate('created_at', '>=', $startOfMonth)
                                ->whereDate('created_at', '<=', $endOfMonth)
                                ->get();

                              $branchWorks = \App\Models\Work::where('department_id', $salary->getRelationValue('user')->department_id)
                                ->whereDate('created_at', '>=', $startOfMonth)
                                ->whereDate('created_at', '<=', $endOfMonth)
                                ->get();

                              $gb = $works->whereIn('service_id', [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48]);
                              $qib = $works->where('service_id', 2);
                              $representation = $works->where('service_id', 5);
                              $cmr = $works->whereIn('service_id', [3,4,7]);
                              $branchGb = $branchWorks->whereIn('service_id', [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48]);
                              $branchQib = $branchWorks->where('service_id', 2);

                              foreach ($gb as $work) {
                                  $totalgb += $work->getParameter(\App\Models\Work::GB);
                              }

                              foreach ($qib as $work) {
                                  $totalqib += $work->getParameter(\App\Models\Work::GB);
                              }
                              foreach ($representation as $work) {
                                  $totalrepresentation += $work->getParameter(\App\Models\Work::AMOUNT) + $work->getParameter(\App\Models\Work::ILLEGALAMOUNT);
                              }
                              foreach ($cmr as $work) {
                                  $totalcmr += $work->getParameter(\App\Models\Work::AMOUNT) + $work->getParameter(\App\Models\Work::ILLEGALAMOUNT);
                              }
                              foreach ($branchGb as $work) {
                                  $totalbranchgb += $work->getParameter(\App\Models\Work::GB);
                              }
                              foreach ($branchQib as $work) {
                                  $totalbranchqib += $work->getParameter(\App\Models\Work::GB);
                              }
                              if(in_array($salary->getRelationValue('user')->getAttribute('id'), [41, 75, 51])) {
                                  $gross = $salary->getRelationValue('user')->bonus + $salary->getRelationValue('user')->gross + ($totalgb * $salary->getRelationValue('user')->coefficient) + ($totalqib * $salary->getRelationValue('user')->qib_coefficient) + ($totalrepresentation * 0.2) + ($totalcmr * 0.1) + ($totalbranchgb * 0.4) + ($totalbranchqib * 0.2);
                              } else {
                                  $gross = $salary->getRelationValue('user')->bonus + $salary->getRelationValue('user')->gross + ($totalgb * $salary->getRelationValue('user')->coefficient) + ($totalqib * $salary->getRelationValue('user')->qib_coefficient) + ($totalrepresentation * 0.2) + ($totalcmr * 0.1);
                              }
                              
                              $workDays = 26;
                              $actualDays = 26;
                              $prize = 0;
                              $vacation = 0;
                              $advance = 0;
                          }
                        @endphp

                        <tr>
                            <th scope="row">{{$loop->iteration}}
                                <input type="hidden" class="user_id" name="user_id" value="{{$salary->getAttribute('user_id')}}">
                                <input type="hidden" class="company_id" name="company_id" value="{{$salary->getAttribute('company_id')}}">
                                @if($isReport)
                                    <input type="hidden" class="salary_report_id" name="salary_report_id" value="{{$salary->getAttribute('id')}}">
                                @endif
                            </th>
                            <td>{{$salary->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{$salary->getRelationValue('user')->getRelationValue('position')->getAttribute('name')}}</td>
                            <td><input type="text" class="salary-{{$salary->getAttribute('id')}}" value="{{$gross}}"></td>
                            <td><input type="text" class="work_days-{{$salary->getAttribute('id')}}" name="work_days" aria-label="work_days" value="{{$workDays}}"></td>
                            <td><input type="text" class="actual_days-{{$salary->getAttribute('id')}}" name="actual_days" aria-label="actual_days" value="{{$actualDays}}"></td>
                            <td class="calculated-salary-{{$salary->getAttribute('id')}}"></td>
                            <td><input class="prize-{{$salary->getAttribute('id')}}" type="text" name="prize" aria-label="prize" value="{{$prize}}"></td>
                            <td><input class="vacation-{{$salary->getAttribute('id')}}" type="text" name="vacation" aria-label="vacation" value="{{$vacation}}"></td>
                            <td class="total-salary"></td>
                            <td class="salary-tax"></td>
                            <td class="employee-fund"></td>
                            <td class="employee-ish"></td>
                            <td class="employee-itsh"></td>
                            <td class="employer-fund"></td>
                            <td class="employer-ish"></td>
                            <td class="employer-itsh"></td>
                            <td class="employee-total-tax-{{$salary->getAttribute('id')}}"></td>
                            <td class="amount-to-paid-{{$salary->getAttribute('id')}}"></td>
                            <td><input type="text" class="advance-{{$salary->getAttribute('id')}}" name="advance" aria-label="advance" value="{{$advance}}"></td>
                            <td class="last-amount-to-paid-{{$salary->getAttribute('id')}}"></td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="7">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center"
                                         role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <br><br>

    <table class="sum-table">
        <tr>
            <th>Fond</th>
            <th>Məbləğ</th>
        </tr>
        <tr>
            <td>Əmək Haqqı fondu</td>
            <td class="font-weight-bold" id="sum-total-salary">0.00</td>
        </tr>
        <tr>
            <td>Gəlir vergisi (İşçidən tutulan)</td>
            <td class="font-weight-bold"  id="sum-salary-tax">0.00</td>
        </tr>
        <tr>
            <td>Pensiya Fondu (İşçidən tutulan)</td>
            <td class="font-weight-bold"  id="sum-employee-fund">0.00</td>
        </tr>
        <tr>
            <td>İşsizlkdən Sığorta Haqqı (İşçidən tutulan)</td>
            <td class="font-weight-bold"  id="sum-employee-ish">0.00</td>
        </tr>
        <tr>
            <td>İcbari Tibbi Sığorta (İşçidən tutulan)</td>
            <td class="font-weight-bold"  id="sum-employee-itsh">0.00</td>
        </tr>
        <tr>
            <td>Pensiya Fondu (İşəgötürən tərəfindən)</td>
            <td class="font-weight-bold"  id="sum-employer-fund">0.00</td>
        </tr>
        <tr>
            <td>İşsizlikdən Sığorta Haqqı (İşəgötürən tərəfindən)</td>
            <td class="font-weight-bold"  id="sum-employer-ish">0.00</td>
        </tr>
        <tr>
            <td>İcbari Tibbi Sığorta (İşəgötürən tərəfindən)</td>
            <td class="font-weight-bold"  id="sum-employer-itsh">0.00</td>
        </tr>

    </table>

    <div class="col-5">
        <br><br><br>
        <h2  class="d-inline font-weight-bold" >Direktor: </h2>
        <p class="d-inline float-right mt-2 font-weight-bolder">_____________________________</p>
        <br>
        <h3 class=" float-right mt-2">M.Y</h3>
    </div>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('tr').each(function () {
                var salaryInput = $(this).find('input[class^="salary-"]');
                var workDaysInput = $(this).find('input[class^="work_days-"]');
                var actualDaysInput = $(this).find('input[class^="actual_days-"]');
                var vacationInput = $(this).find('input[class^="vacation-"]');
                var prizeInput = $(this).find('input[class^="prize-"]');
                var advanceInput = $(this).find('input[class^="advance-"]');
                var calculatedSalary = $(this).find('td[class^="calculated-salary-"]');
                var totalSalary = $(this).find('td[class^="total-salary"]');
                var salaryTax = $(this).find('td[class^="salary-tax"]');
                var employeeIsh = $(this).find('td[class^="employee-ish"]');
                var employeeItsh = $(this).find('td[class^="employee-itsh"]');
                var employeeFund = $(this).find('td[class^="employee-fund"]');
                var employerIsh = $(this).find('td[class^="employer-ish"]');
                var employerItsh = $(this).find('td[class^="employer-itsh"]');
                var employerFund = $(this).find('td[class^="employer-fund"]');
                var employeeTotalTax = $(this).find('td[class^="employee-total-tax"]');
                var amountToPaid = $(this).find('td[class^="amount-to-paid"]');
                var lastAmountToPaid = $(this).find('td[class^="last-amount-to-paid"]');

                updateCalculatedSalary();
                var inputs = [salaryInput, workDaysInput, actualDaysInput, vacationInput, prizeInput, advanceInput];

                inputs.forEach(function (input) {
                    input.on('input', function () {
                        updateCalculatedSalary();
                    });
                });

                function updateCalculatedSalary() {
                    var salary = parseFloat(salaryInput.val()) || 0;
                    var workDays = parseFloat(workDaysInput.val()) || 0;
                    var prize = parseFloat(prizeInput.val()) || 0;
                    var vacation = parseFloat(vacationInput.val()) || 0;
                    var actualDays = parseFloat(actualDaysInput.val()) || 0;
                    var advance = parseFloat(advanceInput.val()) || 0;
                    var newCalculatedSalary = (salary / workDays) * actualDays;
                    var newTotalSalary = newCalculatedSalary + prize + vacation;
                    var newSalaryTax = 0;
                    if (newTotalSalary <= 200) {
                        var newEmployeeFund = newTotalSalary * 0.03;
                    } else {
                        var newEmployeeFund = 6 + (newTotalSalary - 200) * 0.10;
                    }
                    var newEmployeeIsh = newTotalSalary * 0.5/100;

                    if (newTotalSalary <= 8000) {
                        var newEmployeeItsh = newTotalSalary * 0.02;
                    } else {
                        var newEmployeeItsh = 80 + (newTotalSalary - 8000) * 0.005;
                    }

                    if (newTotalSalary <= 200) {
                        var  newEmployerFund = newTotalSalary * 0.22;
                    } else {
                        var   newEmployerFund = 44 + (newTotalSalary - 200) * 0.15;
                    }
                    var newEmployerIsh = newTotalSalary * 0.5/100;

                    if (newTotalSalary <= 8000) {
                        var newEmployerItsh = newTotalSalary * 0.02;
                    } else {
                        var newEmployerItsh = 80 + (newTotalSalary - 8000) * 0.005;
                    }
                    var newEmployeeTotalTax = newEmployeeFund + newEmployeeIsh + newEmployeeItsh + newSalaryTax
                    var newAmountToPaid = newTotalSalary - newEmployeeTotalTax
                    var newLastAmountToPaid = newAmountToPaid - advance

                    calculatedSalary.text(newCalculatedSalary.toFixed(2));
                    salaryTax.text(newSalaryTax.toFixed(2));
                    totalSalary.text(newTotalSalary.toFixed(2));
                    employerFund.text(newEmployerFund.toFixed(2));
                    employerIsh.text(newEmployerIsh.toFixed(2));
                    employerItsh.text(newEmployerItsh.toFixed(2));
                    employeeFund.text(newEmployeeFund.toFixed(2));
                    employeeIsh.text(newEmployeeIsh.toFixed(2));
                    employeeItsh.text(newEmployeeItsh.toFixed(2));
                    employeeItsh.text(newEmployeeItsh.toFixed(2));
                    employeeTotalTax.text(newEmployeeTotalTax.toFixed(2));
                    amountToPaid.text(newAmountToPaid.toFixed(2));
                    lastAmountToPaid.text(newLastAmountToPaid.toFixed(2));
                    updateTotals('total-salary', 'sum-total-salary');
                    updateTotals('salary-tax', 'sum-salary-tax');
                    updateTotals('employee-fund', 'sum-employee-fund');
                    updateTotals('employee-ish', 'sum-employee-ish');
                    updateTotals('employee-itsh', 'sum-employee-itsh');
                    updateTotals('employer-fund', 'sum-employer-fund');
                    updateTotals('employer-ish', 'sum-employer-ish');
                    updateTotals('employer-itsh', 'sum-employer-itsh');
                }

                function updateTotals(columnClass, sumId) {
                    var sum = 0;

                    $('.' + columnClass).each(function() {
                        var cellValue = $(this).text();

                        if (!isNaN(cellValue) && cellValue !== '') {
                            sum += parseFloat(cellValue);
                        }
                    });

                    $('#' + sumId).text(sum.toFixed(2));
                }
            });

            $('#saveButton').on('click', function () {
                saveSalaryReports();
                $(this).hide()
            });

            function saveSalaryReports() {
                var dateStr = '{{ $date }}'; // Controller-dən gələn tarix

                $('#tblNewAttendees tr').each(function () {
                    var salaryReportId = $(this).find('input[class^="salary_report_id"]').val();
                    var rowData = {
                        user_id: $(this).find('input[class^="user_id"]').val(),
                        company_id: $(this).find('input[class^="company_id"]').val(),
                        salary: $(this).find('input[class^="salary-"]').val(),
                        working_days: $(this).find('input[class^="work_days-"]').val(),
                        actual_days: $(this).find('input[class^="actual_days-"]').val(),
                        vacation : $(this).find('input[class^="vacation-"]').val(),
                        prize : $(this).find('input[class^="prize-"]').val(),
                        advance : $(this).find('input[class^="advance-"]').val(),
                        date : dateStr,
                        note : '',
                    };
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
                    var url = '{{ route("salary-reports.store") }}';
                    var method = 'POST';
                    
                    // Əgər salary_report_id varsa, update et
                    if (salaryReportId) {
                        url = '{{ route("salary-reports.update", ":id") }}'.replace(':id', salaryReportId);
                        method = 'POST';
                        rowData._method = 'PUT';
                    }
                    
                    $.ajax({
                        url: url,
                        method: method,
                        data: rowData,
                        success: function (response) {
                            console.log('Data saved successfully:', response);
                        },
                        error: function (error) {
                            console.error('Error saving data:', error);
                        }
                    });
                });
            }
        });
    </script>
@endsection