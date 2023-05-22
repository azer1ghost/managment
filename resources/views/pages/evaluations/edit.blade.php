@extends('layouts.main')

@section('title', trans('translates.navbar.evaluation'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('evaluations.index')">
            @lang('translates.navbar.evaluation')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('name')}}
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
                    <input type="hidden" name="supplier_id" value="1">
                    <x-input::number name="quality" :label="trans('translates.columns.quality')" :value="$data->getAttribute('quality')" width="6" class="pr-3"/>
                    <x-input::number name="delivery" :label="trans('translates.columns.delivery')" :value="$data->getAttribute('delivery')" width="6" class="pr-3"/>
                    <x-input::number name="distributor" :label="trans('translates.columns.distributor')" :value="$data->getAttribute('distributor')" width="6" class="pr-3"/>
                    <x-input::number name="availability" :label="trans('translates.columns.availability')" :value="$data->getAttribute('availability')" width="6" class="pr-3"/>
                    <x-input::number name="certificate" :label="trans('translates.columns.certificate')" :value="$data->getAttribute('certificate')" width="6" class="pr-3"/>
                    <x-input::number name="support" :label="trans('translates.columns.support')" :value="$data->getAttribute('support')" width="6" class="pr-3"/>
                    <x-input::number name="price" :label="trans('translates.columns.price')" :value="$data->getAttribute('price')" width="6" class="pr-3"/>
                    <x-input::number name="payment" :label="trans('translates.columns.payment')" :value="$data->getAttribute('payment')" width="6" class="pr-3"/>
                    <x-input::number name="returning" :label="trans('translates.columns.returning')" :value="$data->getAttribute('returning')" width="6" class="pr-3"/>
                    <x-input::number name="replacement" :label="trans('translates.columns.replacement')" :value="$data->getAttribute('replacement')" width="6" class="pr-3"/>
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')

    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
