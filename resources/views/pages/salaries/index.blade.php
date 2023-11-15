@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))
@section('style')
    <style>
        table {
            text-align: center;
            width: 100%;
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

{{--    <form action="{{route('salaries.index')}}">--}}
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control"
                           placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])"
                           aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('salaries.index')}}"><i
                                    class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-8 col-md-3  mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected
                                @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Salary::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right"
                       href="{{route('salaries.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12 table-container">
                <table class="table table-responsive-sm table-hover table-bordered">
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
                            <th scope="col">yekun ödənilməli məbləğ</th>
                        </tr>
                    </thead>
                    <tbody id="tblNewAttendees">
                    @forelse($salaries as $salary)
                        @php
                          $gross = 0;
                          $net = 0;
                          $totalgb = 0;
                          $totalqib = 0;
                          $totalrepresentation = 0;
                          $totalcmr = 0;
                          $totalbranchgb = 0;
                          $totalbranchqib = 0;

                          $works = \App\Models\Work::where('user_id', $salary->getRelationValue('user')->id)
                              ->whereDate('created_at', '>=', now()->startOfMonth())
                              ->get();
                         $branchWorks = \App\Models\Work::where('department_id' ,$salary->getRelationValue('user')->department_id)
                                    ->whereDate('created_at', '>=', now()->startOfMonth())
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
                            }else {
                            $gross = $salary->getRelationValue('user')->bonus + $salary->getRelationValue('user')->gross + ($totalgb * $salary->getRelationValue('user')->coefficient) + ($totalqib * $salary->getRelationValue('user')->qib_coefficient) + ($totalrepresentation * 0.2) + ($totalcmr * 0.1);
                            }
                             if($gross  <= 200){
                                $net = $gross - ($gross * 0.03) - ($gross * 0.005) - ($gross * 0.02);
                               }
                            else{
                               $net = $gross - (6 + (($gross-200)  * 0.1)) - ($gross * 0.005) - ($gross * 0.02);
                            }
                        @endphp

                        <tr>
                            <th scope="row">{{$loop->iteration}}
                                <input type="hidden" class="user_id" name="user_id" value="{{$salary->getAttribute('user_id')}}">
                                <input type="hidden" class="company_id" name="company_id" value="{{$salary->getAttribute('company_id')}}">
                            </th>
                            <td>{{$salary->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{$salary->getRelationValue('user')->getRelationValue('position')->getAttribute('name')}}</td>
                            <td><input type="text" class="salary-{{$salary->getAttribute('id')}}" value="{{$gross}}"></td>
                            <td><input type="text" class="work_days-{{$salary->getAttribute('id')}}" name="work_days" aria-label="work_days" value="26"></td>
                            <td><input type="text" class="actual_days-{{$salary->getAttribute('id')}}" name="actual_days" aria-label="actual_days" value="26"></td>
                            <td class="calculated-salary-{{$salary->getAttribute('id')}}"></td>
                            <td><input class="prize-{{$salary->getAttribute('id')}}" type="text" name="prize" aria-label="prize" value="0"></td>
                            <td><input class="vacation-{{$salary->getAttribute('id')}}" type="text" name="vacation" aria-label="vacation" value="0"></td>
                            <td class="total-salary-{{$salary->getAttribute('id')}}"></td>
                            <td class="salary-tax-{{$salary->getAttribute('id')}}"></td>
                            <td class="employee-fund-{{$salary->getAttribute('id')}}"></td>
                            <td class="employee-ish-{{$salary->getAttribute('id')}}"></td>
                            <td class="employee-itsh-{{$salary->getAttribute('id')}}"></td>
                            <td class="employer-fund-{{$salary->getAttribute('id')}}"></td>
                            <td class="employer-ish-{{$salary->getAttribute('id')}}"></td>
                            <td class="employer-itsh-{{$salary->getAttribute('id')}}"></td>
                            <td class="employee-total-tax-{{$salary->getAttribute('id')}}"></td>
                            <td class="amount-to-paid-{{$salary->getAttribute('id')}}"></td>
                            <td><input type="text" class="advance-{{$salary->getAttribute('id')}}" name="advance" aria-label="advance"></td>
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
            <div class="col-6">
                <div class="float-right">
                    {{$salaries->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
{{--    </form>--}}

@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>

    <script>
        $(document).ready(function () {


            // Iterate through each row in the table
            $('tr').each(function () {

                // Find the salary input and work-days input within the current row
                var salaryInput = $(this).find('input[class^="salary-"]');
                var workDaysInput = $(this).find('input[class^="work_days-"]');
                var actualDaysInput = $(this).find('input[class^="actual_days-"]');
                var vacationInput = $(this).find('input[class^="vacation-"]');
                var prizeInput = $(this).find('input[class^="prize-"]');
                var advanceInput = $(this).find('input[class^="advance-"]');
                var calculatedSalary = $(this).find('td[class^="calculated-salary-"]');
                var totalSalary = $(this).find('td[class^="total-salary-"]');
                var salaryTax = $(this).find('td[class^="salary-tax-"]');
                var employeeIsh = $(this).find('td[class^="employee-ish-"]');
                var employeeItsh = $(this).find('td[class^="employee-itsh-"]');
                var employeeFund = $(this).find('td[class^="employee-fund-"]');
                var employerIsh = $(this).find('td[class^="employer-ish-"]');
                var employerItsh = $(this).find('td[class^="employer-itsh-"]');
                var employerFund = $(this).find('td[class^="employer-fund-"]');
                var employeeTotalTax = $(this).find('td[class^="employee-total-tax"]');
                var amountToPaid = $(this).find('td[class^="amount-to-paid"]');
                var lastAmountToPaid = $(this).find('td[class^="last-amount-to-paid"]');

                updateCalculatedSalary();
                salaryInput.on('input', function () {
                    updateCalculatedSalary();
                });

                workDaysInput.on('input', function () {
                    updateCalculatedSalary();
                });

                actualDaysInput.on('input', function () {
                    updateCalculatedSalary();
                });

                vacationInput.on('input', function () {
                    updateCalculatedSalary();
                });

                prizeInput.on('input', function () {
                    updateCalculatedSalary();
                });
                advanceInput.on('input', function () {
                    updateCalculatedSalary();
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
                }
            });
            $('#saveButton').on('click', function () {
                saveSalaryReports();
                $(this).hide()
            });

            function saveSalaryReports() {
                let date = new Date()

                $('#tblNewAttendees tr').each(function () {
                    var rowData = {
                        user_id: $(this).find('input[class^="user_id"]').val(),
                        company_id: $(this).find('input[class^="company_id"]').val(),
                        salary: $(this).find('input[class^="salary-"]').val(),
                        working_days: $(this).find('input[class^="work_days-"]').val(),
                        actual_days: $(this).find('input[class^="actual_days-"]').val(),
                        vacation : $(this).find('input[class^="vacation-"]').val(),
                        prize : $(this).find('input[class^="prize-"]').val(),
                        advance : $(this).find('input[class^="advance-"]').val(),
                        date : date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate(),
                        note : '',
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("salary-reports.store") }}',
                        method: 'POST',
                        data: rowData,
                        success: function (response) {
                            console.log('Data saved successfully:', response);
                        },
                        error: function (error) {
                            console.error('Error saving data:', error);
                            console.log(rowData)
                        }
                    });
                });
            }
        });
    </script>
@endsection