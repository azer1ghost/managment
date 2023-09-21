@extends('layouts.main')

@section('title', trans('translates.navbar.creditor'))

@section('style')
    <style>
        body{
            text-align: center;
        }

        img{
            width: 25px;
            height: auto;
        }
    </style>

@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.creditor')
        </x-bread-crumb-link>
    </x-bread-crumb>

        <div class="row d-flex justify-content-between mb-2">
            <form action="{{route('creditors.index')}}" class="row col-12">
                <div class="col-md-3">
                    <div class="input-group mb-3">

                        <input type="search" name="search" value="{{$filters['search']}}" class="form-control"
                               placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.client')])"
                               aria-label="Recipient's clientname" aria-describedby="basic-addon2">

                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                            <a class="btn btn-outline-danger d-flex align-items-center"
                               href="{{route('creditors.index')}}"><i
                                        class="fal fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <select id="data-supplier" name="supplier_id" class="form-control"
                                data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.clients.selectSupplier')">
                            <option value=""> @lang('translates.filters.supplier') </option>
                            @foreach($suppliers as $supplier)
                                <option
                                        @if($filters['supplier'] == $supplier->getAttribute('id')) selected @endif
                                value="{{$supplier->getAttribute('id')}}">
                                    {{$supplier->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-12 col-md-3" wire:ignore>
                    <select name="status" id="data-status" class="form-control">
                        <option value="">@lang('translates.general.status_choose')</option>
                        @foreach($statuses as $key => $status)
                            <option
                                    @if($filters['status'] == $status ) selected
                                    @endif value="{{$status}}"
                            >
                                @lang('translates.creditors.statuses.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-outline-primary"><i
                                    class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('creditors.index')}}" class="btn btn-outline-danger"><i
                                    class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                    </div>
                </div>


                <div class="col-8 pt-2 col-md-3  mb-3">
                    <select name="limit" class="custom-select">
                        @foreach([25, 50, 100, 250] as $size)
                            <option @if(request()->get('limit') == $size) selected
                                    @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 p-0 pr-3 pb-3 mt-4">
                    {{--                    @can('create', App\Models\Creditor::class)--}}
                    <a class="btn btn-outline-success float-right " href="{{route('creditors.create')}}">@lang('translates.buttons.create')</a>
                    {{--                    @endcan--}}
                    {{--                    @if(auth()->user()->hasPermission('canExport-client'))--}}
                    {{--                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('clients.export', ['filters' => json_encode($filters)])}}">@lang('translates.buttons.export')</a>--}}
                    {{--                    @endif--}}
                </div>
            </form>

            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.supplier')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.general.earning')</th>
                        <th scope="col">@lang('translates.columns.vat')</th>
                        <th scope="col">@lang('translates.columns.paid')</th>
                        <th scope="col">@lang('translates.columns.vat_paid')</th>
                        <th scope="col">@lang('translates.columns.last_paid')</th>
                        <th scope="col">Qaimə</th>
                        <th scope="col">Qaimə yaranma tarixi</th>
                        <th scope="col">@lang('translates.columns.status')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($creditors as $creditor)
                        <tr class="clickable" data-rowid="{{$loop->index + 1}}" @if($creditor->getAttribute('painted') == 1) style="background-color: red" @endif>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$creditor->getAttribute('supplier_id') > 0 ? $creditor->getRelationValue('supplier')->getAttribute('name') : $creditor->getAttribute('creditor')}}</td>
                            <td>{{$creditor->getRelationValue('company')->getAttribute('name')}}</td>
                            <td  data-id="{{$creditor->getAttribute('id')}}"
                            {{-- class="amount" contenteditable="true"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46"--}}
                            >{{$creditor->getAttribute('amount')}}</td>
                            <td  data-id="{{$creditor->getAttribute('id')}}"
                            {{-- class="vat" contenteditable="true" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46" --}}
                            >{{$creditor->getAttribute('vat')}}</td>
                            <td>{{$creditor->getAttribute('paid')}}</td>
                            <td>{{$creditor->getAttribute('vat_paid')}}</td>
                            <td>{{$creditor->getAttribute('last_date')}}</td>
                            <td>{{$creditor->getAttribute('overhead')}}</td>
                            <td>{{$creditor->getAttribute('overhead_at')}}</td>
                            <td>
                                <span class="badge {{$creditor->getAttribute('status') == 1 ? 'badge-danger' : ($creditor->getAttribute('status') == 2 ? 'badge-success' : 'badge-warning')}}"> {{trans('translates.creditors.statuses.'.$creditor->getAttribute('status'))}}</span>
                            </td>
                            <td>{{$creditor->getAttribute('note')}}</td>
                                <td>
                                <div class="btn-sm-group">
                                    @can('view', $creditor)
                                        <a href="{{route('creditors.create', ['id' => $creditor])}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-copy"></i>
                                        </a>
                                    @endcan
                                    @can('view', $creditor)
                                        <a href="{{route('creditors.show', $creditor)}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
    {{--                                    @can('update', $creditor)--}}
                                            <a href="{{route('creditors.edit', $creditor)}}"
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
    {{--                                    @endcan--}}
                                        @can('delete', $creditor)
                                            <a href="{{route('creditors.destroy', $creditor)}}" delete
                                               data-name="{{$creditor->getAttribute('name')}}"
                                               class="btn btn-sm btn-outline-danger">
                                                <i class="fal fa-trash"></i>
                                            </a>
                                        @endcan

                                </div>
                            </td>
                            <td>
                                <button type="button" class="colorButton btn btn-primary" data-creditors='@json($creditor)'>
                                    @if($creditor->getAttribute('painted') == 1)
                                        Boyanı sil
                                    @else
                                        Boya
                                    @endif
                                </button>
                            </td><td>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop-{{$creditor->getAttribute('id')}}">
                                  @lang('translates.columns.payment')
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="staticBackdrop-{{$creditor->getAttribute('id')}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">@lang("translates.columns.payment")</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('creditor.payment') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="{{$creditor->getAttribute('id')}}">
                                            <div class="form-group">
                                                <label for="paid">@lang('translates.columns.paid')</label>
                                                <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46" class="form-control" name="paid" id="paid">
                                            </div>
                                            <div class="form-group">
                                                <label for="vat_paid">@lang('translates.columns.vat_paid')</label>
                                                <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46" class="form-control" name="vat_paid" id="vat_paid">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                                            <button type="submit" class="btn btn-primary">@lang('translates.buttons.send')</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
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
                        <td colspan="3"></td>
                        <td><strong>@lang('translates.general.earning'): {{ $creditors->sum('amount') }}</strong></td>
                        <td><strong>@lang('translates.columns.vat'): {{ $creditors->sum('vat') }}</strong></td>
                        <td><strong>@lang('translates.columns.paid'): {{ $creditors->sum('paid') }}</strong></td>
                        <td><strong>@lang('translates.columns.vat_paid'): {{ $creditors->sum('vat_paid') }}</strong></td>
                        <td><strong>@lang('translates.columns.total'): {{ $creditors->sum('amount') + $creditors->sum('vat') }}</strong></td>
                        <td colspan="4"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$creditors->appends(request()->input())->links()}}
                </div>
            </div>
        </div>

@endsection
@section('scripts')

    <script>

        function getCreditors() {
            let creditors = $(this).data('creditors');
        }
        $('.colorButton').on('click', function (e) {
            getCreditors()
            let creditors = $(this).data('creditors');
            let column = $(this).parent().parent();
            let button = $(this)
            let paintValue = ''
            let buttonName = ''
            if (column.css('background-color') === 'rgb(255, 0, 0)') {
                paintValue = 0
                buttonName = 'Boya'
                column.css('background-color', 'rgb(245,247,255)');
            } else {
                column.css('background-color', 'red');
                paintValue = 1
                buttonName = 'Boyanı sil'
            }
            $.ajax({
                url: '/module/creditors/updateColor',
                type: 'POST',
                data: {
                    id: creditors.id,
                    painted: paintValue
                },
                success: function (response) {
                    button.html(buttonName)
                    console.log('Painted:', response);
                },
                error: function (error) {
                    console.log('There is a problem:', error);
                }
            });
        });

        // $('.amount').on('blur', function () {
        //     var id = $(this).data('id');
        //     var amount = $(this).text();
        //     $.ajax({
        //         url: '/module/creditors/updateAmount',
        //         type: 'POST',
        //         data: {
        //             id: id,
        //             amount: amount,
        //         },
        //         success: function (response) {
        //             console.log('amount changed:', response);
        //         },
        //         error: function (error) {
        //             console.log('there is a problem:', error);
        //         }
        //     });
        // });

        {{--        $('.vat').on('blur', function () {--}}
        {{--            var id = $(this).data('id');--}}
        {{--            var vat = $(this).text();--}}
        {{--            $.ajax({--}}
        {{--                url: '/module/creditors/updateVat',--}}
        {{--                type: 'POST',--}}
        {{--                data: {--}}
        {{--                    id: id,--}}
        {{--                    vat: vat,--}}
        {{--                },--}}
        {{--                success: function (response) {--}}
        {{--                    console.log('amount changed:', response);--}}
        {{--                },--}}
        {{--                error: function (error) {--}}
        {{--                    console.log('there is a problem:', error);--}}
        {{--                }--}}
        {{--            });--}}
        {{--        });--}}
    </script>
@endsection