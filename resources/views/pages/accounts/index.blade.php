@extends('layouts.main')

@section('title', trans('translates.navbar.account'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.account')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('banks.index')}}">
        <div class="row d-flex justify-content-between mb-2">

                <div class="col-md-6">
                    <div class="input-group">

                        <input type="search" name="search" value="{{$filters['search']}}" class="form-control"
                               placeholder="@lang('translates.fields.enter', ['field' => trans('translates.navbar.account')])">

                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                            <a class="btn btn-outline-danger d-flex align-items-center"
                               href="{{route('banks.index')}}"><i
                                        class="fal fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select id="data-company" name="company_id" class="form-control"
                                data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.clients.selectCompany')">
                            <option value=""> @lang('translates.filters.company') </option>
                            @foreach($companies as $company)
                                <option
                                        @if($filters['company'] == $company->getAttribute('id')) selected @endif
                                value="{{$company->getAttribute('id')}}">
                                    {{$company->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-outline-primary"><i
                                    class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('banks.index')}}" class="btn btn-outline-danger"><i
                                    class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                    </div>
                </div>


                <div class="col-12 p-0 pr-3 pb-3 mt-4">
                    @if(auth()->user()->isDeveloper())
                        <a class="btn btn-outline-success float-right " href="{{route('banks.create')}}">@lang('translates.buttons.create')</a>
                    @endif
                </div>
            <div class="col-12">
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">@lang('translates.navbar.account')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">Valyuta</th>
                        <th scope="col">@lang('translates.general.earning')</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($accounts as $account)
                        <tr>
                            <td>{{$account->getAttribute('name')}}</td>
                            <td>{{$account->getAttribute('company_id') > 0 ? $account->getRelationValue('company')->getAttribute('name') : $account->getAttribute('customCompany')}}</td>
                            <td>{{$account->getAttribute('currency')}}</td>
                            <td class="amount" data-id="{{$account->getAttribute('id')}}" contenteditable="true"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46">{{$account->getAttribute('amount')}}</td>
                        @if(auth()->user()->isDeveloper())
                                <td>
                                    <div class="btn-sm-group">
                                        <a href="{{route('banks.show', $account)}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>


                                        <a href="{{route('banks.edit', $account)}}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>

                                        <a href="{{route('banks.destroy', $account)}}" delete
                                           data-name="{{$account->getAttribute('name')}}"
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            @endif


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
                    <tr>
                        <td colspan="2"></td>
                        <td><strong>@lang('translates.columns.total'): {{ $accounts->sum('amount') }}</strong></td>

                    </tr>
                    </tbody>
                </table>
                </div>
            </div>

        </div>
    </form>

@endsection
@section('scripts')
    <script>
        $('.amount').on('blur', function () {
            var id = $(this).data('id');
            var amount = $(this).text();
            $.ajax({
                url: '/module/banks/updateBankAmount',
                type: 'POST',
                data: {
                    id: id,
                    amount: amount,
                },
                success: function (response) {
                    console.log('amount changed:', response);
                },
                error: function (error) {
                    console.log('there is a problem:', error);
                }
            });
        });

    </script>
{{--    <script>--}}
{{--        $(function () {--}}
{{--            $('#sortable').sortable({--}}
{{--                axis: 'y',--}}
{{--                handle: ".sortable",--}}
{{--                update: function () {--}}
{{--                    var data = $(this).sortable('serialize');--}}
{{--                    $.ajax({--}}
{{--                        type: "POST",--}}
{{--                        data: data,--}}
{{--                        url: "{{route('bank.sortable')}}",--}}
{{--                    });--}}
{{--                }--}}
{{--            });--}}
{{--            $('#sortable').disableSelection();--}}
{{--        });--}}
{{--    </script>--}}

@endsection