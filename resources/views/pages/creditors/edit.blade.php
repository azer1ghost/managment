@extends('layouts.main')

@section('title', trans('translates.navbar.creditor'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('creditors.index')">
            @lang('translates.navbar.creditor')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getRelationValue('supplier')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class=" row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <div class="form-group col-6">
                        <label>@lang('translates.columns.supplier')</label>
                        <select name="supplier_id" data-url="{{route('suppliers.search')}}" class="custom-select2" style="width: 100% !important;">
                            @if(is_numeric(optional($data)->getAttribute('supplier_id')))
                                <option value="{{optional($data)->getAttribute('supplier_id')}}">{{optional($data)->getRelationValue('supplier')->getAttribute('name')}}</option>
                            @endif
                        </select>
                    </div>
                    <x-input::text name="creditor" :value="$data->getAttribute('creditor')" :label="trans('translates.columns.supplier')" width="6" class="pr-3 creditor" />
                    <x-input::select name="company_id" :value="$data->getAttribute('company_id')" :label="trans('translates.columns.company')"  width="6" class="pr-3" :options="$companies"/>
                    <x-input::text name="amount" :label="trans('translates.columns.amount')" :value="$data->getAttribute('amount')" width="6" class="pr-2 amount" />
                    <x-input::text name="vat" :label="trans('translates.columns.vat')" :value="$data->getAttribute('vat')" width="6" class="pr-2 vat" />
                    <x-input::text name="overhead" :value="$data->getAttribute('overhead')" :label="trans('translates.columns.overhead')" width="6" class="pr-3 creditor" />
                    <div class="form-group col-6">
                        <div class="custom-control custom-switch mb-5">
                            <input type="checkbox" name="doc" class="custom-control-input" id="doc" @if($data->getAttribute('doc')) checked @endif>
                            <label class="custom-control-label" for="doc">Sənəd var</label>
                        </div>
                        <label>@lang('translates.columns.status')</label>
                        <select name="status" class="form-control">
                        @foreach($statuses as $status)
                                <option value="{{$status}}" @if($data->getAttribute('status') == $status) selected @endif>{{trans('translates.creditors.statuses.'.$status)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-input::date name="last_date" :label="trans('translates.columns.last_paid')" :value="$data->getAttribute('last_date')" width="6" class="pr-2" />
                    <x-input::date name="overhead_at" :label="trans('translates.columns.overhead_at')" :value="$data->getAttribute('overhead_at')" width="6" class="pr-2" />
                    <x-input::textarea name="note" :label="trans('translates.fields.note')" :value="$data->getAttribute('note')" width="6" class="pr-2" />
            </div>
        </div>

        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
    @if(!is_null($data))
        <div class="col-12">
            <x-documents :documents="$data->documents"/>
            @if(!is_null($data->id))
                <x-document-upload :id="$data->id" model="Creditor"/>
            @endif
        </div>
    @endif
@endsection
@section('scripts')

    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
    <script>
        $('.amount input').on('change', function() {
            var amount = parseFloat($(this).val());
            var vatRate = 0.18; // VAT oranını kendinize göre ayarlayın

            var vat = amount * vatRate;
            $('.vat input').val(vat.toFixed(2));
        });

        // Supplier select değiştiğinde
        $('select[name="supplier_id"]').change(function() {
            var selectedSupplierId = $(this).val();

            if (selectedSupplierId > 0) {
                // Creditor inputunu gizle
                $('.creditor').hide();
            } else {
                // Creditor inputunu göster
                $('.creditor').show();
            }
        });

        // Sayfa yüklendiğinde supplier select'in değerine göre creditor inputunu ayarla
        // var selectedSupplierId = $('select[name="supplier_id"]').val();
        // if (selectedSupplierId !== '') {
        //     $('.creditor').hide();
        // }
    </script>

@endsection
