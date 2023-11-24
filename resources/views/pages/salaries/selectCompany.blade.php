@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))

@section('content')
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">@lang('translates.clients.selectCompany')</h5>
                </div>
                <form action="@if(request()->url() == (route('selectCompany-salary')))
                     {{route('salaries.index')}}
                        @else {{route('salary-reports.index')}}
                      @endif"
                      method="get">
                <div class="modal-body">

                        <div class="form-group">
                            @if(request()->url() !== (route('selectCompany-salary')))
                                <select  class="form-control mb-2" name="date-salary">
                                    @php
                                        $currentMonth = now()->format('m');
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

                                    @foreach($months as $monthNumber => $monthName)
                                        <option value="{{ $monthNumber }}" {{ $currentMonth == $monthNumber ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <select class="form-control" name="company_id">
                                @foreach(\App\Models\Company::get(['id', 'name']) as $company)
                                    <option value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('translates.buttons.search')</button>
                </div>
                </form>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#staticBackdrop').modal('toggle')
        });
    </script>
@endsection