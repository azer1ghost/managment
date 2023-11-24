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
                                <select  class="form-control" name="date-salary">
                                    <option value="01">Yanvar</option>
                                    <option value="02">Fevral</option>
                                    <option value="03">Mart</option>
                                    <option value="04">Aprel</option>
                                    <option value="05">May</option>
                                    <option value="06">İyun</option>
                                    <option value="07">İyul</option>
                                    <option value="08">Avqust</option>
                                    <option value="09">Sentyabr</option>
                                    <option value="10">Oktyabr</option>
                                    <option value="11">Noyabr</option>
                                    <option value="12">Dekabr</option>
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