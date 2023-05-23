@extends('layouts.main')

@section('title', trans('translates.navbar.supplier'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('suppliers.index')">
            @lang('translates.navbar.supplier')
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
                    <x-input::text name="name" :label="trans('translates.columns.name')" :value="$data->getAttribute('name')" width="6" class="pr-3"/>
                    <x-input::text name="voen" label="voen" :value="$data->getAttribute('voen')" width="6" class="pr-2" />
                    <x-input::text name="phone" :label="trans('translates.columns.phone')" :value="$data->getAttribute('phone')" width="6" class="pr-2" />
                    <x-input::text name="email" :label="trans('translates.columns.email')" :value="$data->getAttribute('email')" width="6" class="pr-2" />
                    <x-input::textarea name="note" :label="trans('translates.placeholders.note')" :value="$data->getAttribute('note')" width="6" class="pr-2" />
                    <div class="custom-control custom-switch mb-5">
                        <input type="checkbox" name="is_service" class="custom-control-input" id="is_service" @if($data->getAttribute('is_service') || $method == 'POST' ) checked @endif>
                        <label class="custom-control-label" for="is_service">@lang('translates.buttons.is_service')</label>
                    </div>
                </div>
            </div>
        </div>
        @if($method != 'POST')
            @if($data->getAttribute('is_service') == 1)
                <x-input::number name="quality" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.quality')" :value="$data->getAttribute('quality')" width="6" class="pr-3"/>
                <x-input::number name="delivery" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.delivery')" :value="$data->getAttribute('delivery')" width="6" class="pr-3"/>
                <x-input::number name="distributor" oninput="calculateNonEmptyCount()"  :label="trans('translates.columns.distributor')" :value="$data->getAttribute('distributor')" width="6" class="pr-3"/>
                <x-input::number name="availability" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.availability')" :value="$data->getAttribute('availability')" width="6" class="pr-3"/>
                <x-input::number name="certificate" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.certificate')" :value="$data->getAttribute('certificate')" width="6" class="pr-3"/>
            @endif
            <x-input::number name="support" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.support')" :value="$data->getAttribute('support')" width="6" class="pr-3"/>
            <x-input::number name="price" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.price')" :value="$data->getAttribute('price')" width="6" class="pr-3"/>
            <x-input::number name="payment" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.payment')" :value="$data->getAttribute('payment')" width="6" class="pr-3"/>
            <x-input::number name="returning" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.returning')" :value="$data->getAttribute('returning')" width="6" class="pr-3"/>
            <x-input::number name="replacement" oninput="calculateNonEmptyCount()" :label="trans('translates.columns.replacement')" :value="$data->getAttribute('replacement')" width="6" class="pr-3"/>
        @endif
        <p id="result"></p>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>



    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.navbar.document')" />
            <x-document-upload :id="$data->id" model="Supplier"/>
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
        function calculateNonEmptyCount() {
            let inputs = document.querySelectorAll('input[type="number"]');
            let count = 0;

            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value.trim() !== '') {
                    count++;
                }
            }

            document.getElementById("result").innerText = "Boş Olmayan Input Sayısı: " + count;
        }
    </script>
@endsection
