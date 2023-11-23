@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))

@section('content')
       <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">@lang('translates.clients.selectCompany')</h5>
                </div>
                <div class="modal-body">
                    <form action="{{route('salaries.index')}}" method="get">
                    <div class="form-group">
                        <select class="form-control" name="company_id">
                            @foreach(\App\Models\Company::get(['id', 'name']) as $company)
                                <option value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });

        $(document).ready(function () {
            $('#staticBackdrop').modal('toggle')
        });
    </script>
@endsection