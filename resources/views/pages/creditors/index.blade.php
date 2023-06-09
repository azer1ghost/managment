@extends('layouts.main')

@section('title', trans('translates.navbar.creditor'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.creditor')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('creditors.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">

                    <input type="search" name="search" value="{{$filters['search']}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.client')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">

                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('creditors.index')}}"><i
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
            @can('create', App\Models\Creditor::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right"
                       href="{{route('creditors.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.supplier')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.amount')</th>
                        <th scope="col">@lang('translates.columns.vat')</th>
                        <th scope="col">@lang('translates.columns.last_paid')</th>
                        <th scope="col">@lang('translates.columns.status')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($creditors as $creditor)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$creditor->getRelationValue('supplier')->getAttribute('name')}}</td>
                            <td>{{$creditor->getRelationValue('company')->getAttribute('name')}}</td>
                            <td class="amount" data-id="{{$creditor->getAttribute('id')}}" contenteditable="true" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46">{{$creditor->getAttribute('amount')}}</td>
                            <td class="vat" data-id="{{$creditor->getAttribute('id')}}" contenteditable="true"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46">{{$creditor->getAttribute('vat')}}</td>
                            <td>{{$creditor->getAttribute('last_date')}}</td>
                            <td ><span class="badge {{$creditor->getAttribute('status') == 1 ? 'badge-danger' : 'badge-success'}}"> {{trans('translates.creditors.statuses.'.$creditor->getAttribute('status'))}}</span></td>
                            <td>{{$creditor->getAttribute('note')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $creditor)
                                        <a href="{{route('creditors.create', ['id' => $creditor])}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-copy"></i>
                                        </a>
                                    @endcan
                                    @can('view', $creditor)
                                        <a href="{{route('creditors.show', $creditor)}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $creditor)
                                        <a href="{{route('creditors.edit', $creditor)}}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $creditor)
                                        <a href="{{route('creditors.destroy', $creditor)}}" delete
                                           data-name="{{$creditor->getAttribute('name')}}"
                                           class="btn btn-sm btn-outline-danger">
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
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
            <div class="col-6">
                <div class="float-right">
                    {{$creditors->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>

@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
        $('.amount').on('blur', function() {
            var id = $(this).data('id');
            var amount = $(this).text();
                $.ajax({
                    url: '/module/creditors/updateAmount',
                    type: 'POST',
                    data: {
                        id: id,
                        amount: amount,
                    },
                    success: function(response) {
                        console.log('amount changed:', response);
                    },
                    error: function(error) {
                        console.log('there is a problem:', error);
                    }
                });
        });

        $('.vat').on('blur', function() {
            var id = $(this).data('id');
            var vat = $(this).text();
                $.ajax({
                    url: '/module/creditors/updateVat',
                    type: 'POST',
                    data: {
                        id: id,
                        vat: vat,
                    },
                    success: function(response) {
                        console.log('amount changed:', response);
                    },
                    error: function(error) {
                        console.log('there is a problem:', error);
                    }
                });
        });
    </script>

@endsection