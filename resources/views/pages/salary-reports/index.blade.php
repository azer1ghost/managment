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

    {{--    <form action="{{route('salaries.index')}}">--}}
    <div class="row d-flex justify-content-between mb-2">

        {{--            <div class="col-md-6">--}}
        {{--                <div class="input-group mb-3">--}}
        {{--                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control"--}}
        {{--                           placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])"--}}
        {{--                           aria-label="Recipient's clientname" aria-describedby="basic-addon2">--}}
        {{--                    <div class="input-group-append">--}}
        {{--                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>--}}
        {{--                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('salaries.index')}}"><i--}}
        {{--                                    class="fal fa-times"></i></a>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        <div class="col-12 float-right mb-2">
            <a class="btn btn-outline-success float-right"
               href="{{route('selectCompany-salary')}}">Yeni Əmək Haqqı Hesabla</a>
        </div>
        {{--    </form>--}}

        <div class="col-12 table-container">
            <table id="table" class="table table-responsive-sm table-hover table-bordered">
                <thead>
                <tr>
                    <th scope="col" colspan="4">Əməkdaş</th>
                    <th scope="col" colspan="6">Hesablanıb</th>
                    <th scope="col" colspan="4">Tutulmuşdur</th>
                    <th scope="col" colspan="3">İşəgötürən Tərəfindən</th>
                    <th scope="col" colspan="5"></th>
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
                    <th scope="col">Əməliyyatlar</th>
                </tr>
                </thead>
                <tbody id="tblNewAttendees">
                @forelse($salaryReports as $salary)

                    <tr>
                        <th scope="row">{{$loop->iteration}}
                            <input type="hidden" class="user_id" name="user_id" value="{{$salary->getAttribute('user_id')}}">
                            <input type="hidden" class="company_id" name="company_id" value="{{$salary->getAttribute('company_id')}}">
                        </th>
                        <td>{{$salary->getRelationValue('user')->getAttribute('fullname')}}</td>
                        <td>{{$salary->getRelationValue('user')->getRelationValue('position')->getAttribute('name')}}</td>
                        <td><input type="text" class="salary-{{$salary->getAttribute('id')}}" value="{{$salary->getAttribute('salary')}}"></td>
                        <td><input type="text" class="work_days-{{$salary->getAttribute('id')}}" name="work_days" aria-label="work_days" value="{{$salary->getAttribute('working_days')}}"></td>
                        <td><input type="text" class="actual_days-{{$salary->getAttribute('id')}}" name="actual_days" aria-label="actual_days" value="{{$salary->getAttribute('actual_days')}}"></td>
                        <td class="calculated-salary-{{$salary->getAttribute('id')}}"></td>
                        <td><input class="prize-{{$salary->getAttribute('id')}}" type="text" name="prize" aria-label="prize" value="{{$salary->getAttribute('prize')}}"></td>
                        <td><input class="vacation-{{$salary->getAttribute('id')}}" type="text" name="vacation" aria-label="vacation" value="{{$salary->getAttribute('vacation')}}"></td>
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
                        <td><input type="text" class="advance-{{$salary->getAttribute('id')}}" name="advance" aria-label="advance" value="{{$salary->getAttribute('advance')}}"></td>
                        <td class="last-amount-to-paid-{{$salary->getAttribute('id')}}"></td>
                        <td>
                            <div class="btn-sm-group d-flex">
                                <a href="#" style="display: none;" class="btn btn-sm btn-outline-success edit-btn edit-btn-{{$salary->getAttribute('id')}}" data-salary-id="{{$salary->getAttribute('id')}}">
                                    <i class="fal fa-pen"></i>
                                </a>
                                <a href="#" style="display: none;" class="btn btn-sm btn-outline-secondary undo-btn undo-btn-{{$salary->getAttribute('id')}}" data-salary="{{$salary}}" data-salary-id="{{$salary->getAttribute('id')}}">
                                    <i class="fal fa-undo"></i>
                                </a>
                                    <a href="{{route('salary-reports.destroy', $salary)}}" delete data-name="{{$salary->getAttribute('user_id')}}" class="btn btn-sm btn-outline-danger" >
                                        <i class="fal fa-trash"></i>
                                    </a>
                            </div>
                        </td>
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
<div class="col-6 float-right">
    <a class="btn btn-outline-primary float-right" href="{{ route('salary-report.export' , [ 'filters' => json_encode($filters),]) }}">@lang('translates.buttons.export')</a>
</div>
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
            function handleInputChange(salaryId) {
                $(".edit-btn-" + salaryId + ", .undo-btn-" + salaryId).hide();

                $("input.salary-" + salaryId + ", input.work_days-" + salaryId + ", input.actual_days-" + salaryId + ", input.prize-" + salaryId + ", input.vacation-" + salaryId + ", input.advance-" + salaryId).on("input", function () {
                    $(".edit-btn-" + salaryId + ", .undo-btn-" + salaryId).show();
                });
            }

            $(".edit-btn").on("click", function (e) {
                e.preventDefault();

                var salaryId = $(this).data("salary-id");
                var rowData = {
                    salary:  $('td').find('input.salary-' + salaryId).val(),
                    working_days:$('td').find('input.work_days-' + salaryId).val(),
                    actual_days: $('td').find('input.actual_days-' + salaryId).val(),
                    vacation : $('td').find('input.vacation-' + salaryId).val(),
                    prize : $('td').find('input.prize-' + salaryId).val(),
                    advance : $('td').find('input.advance-' + salaryId).val(),
                    note : '',
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('salary-reports.update', 'id')}}".replace('id', salaryId),
                    method: 'PUT',
                    data: rowData,
                    success: function (response) {
                        console.log('Data saved successfully:', response);
                    },
                    error: function (error) {
                        console.error('Error saving data:', error);
                    }
                });
                $(".edit-btn-" + salaryId + ", .undo-btn-" + salaryId).hide();

                console.log("Changes saved for salary ID: " + salaryId);
            });

            $(".undo-btn").on("click", function (e) {
                e.preventDefault();

                var salaryId = $(this).data("salary-id");
                var salaryModel = $(this).data("salary");

                $(".edit-btn-" + salaryId + ", .undo-btn-" + salaryId).hide();
                $("input.salary-" + salaryId).val(salaryModel.salary);
                $("input.work_days-" + salaryId).val(salaryModel.working_days);
                $("input.actual_days-" + salaryId).val(salaryModel.actual_days);
                $("input.prize-" + salaryId).val(salaryModel.prize);
                $("input.vacation-" + salaryId).val(salaryModel.vacation);
                $("input.advance-" + salaryId).val(salaryModel.advance);
            });

                @foreach($salaryReports as $salary)
                 handleInputChange({{ $salary->getAttribute('id') }});
                @endforeach
        });

        $(document).ready(function () {

            $('tr').each(function () {
                $(".undo-btn").on("click", function () {
                    updateCalculatedSalary();
                });
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

        });
    </script>
@endsection