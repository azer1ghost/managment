@extends('layouts.main')

@section('title', __('translates.navbar.order'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('orders.index')">
            @lang('translates.navbar.order')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('code')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input name="code" label="Order code" placeholder="Order code daxil edin"/>
                    </x-form-group>

                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input name="service" label="Order service" placeholder="Order service daxil edin"/>
                    </x-form-group>

                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input name="amount" label="Order amount" placeholder="Order amount daxil edin"/>
                    </x-form-group>
                    <div class="col-12 col-md-6 pr-3">
                        <label for="user_id">@lang('translates.general.select_client')</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="" selected disabled>@lang('translates.general.select_client')</option>
                            @foreach($users as $user)
                                <option @if(optional($data)->getAttribute('user_id') === $user->getAttribute('id')) selected
                                        @endif value="{{$user->getAttribute('id')}}">{{$user->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 pr-3">
                        <label for="status">@lang('translates.general.status_choose')</label>
                        <select name="status" id="status" class="form-control">
                            <option value="" selected disabled>@lang('translates.general.status_choose')</option>
                            @foreach($statuses as $status)
                                <option @if(optional($data)->getAttribute('status') == $status) selected
                                        @endif value="{{$status}}">@lang('translates.orders.statuses.'.$status)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="is_paid" class="custom-control-input" id="is_paid" @if($data->getAttribute('is_paid') || $method == 'POST' ) checked @endif>
                        <label class="custom-control-label" for="is_paid">@lang('translates.columns.paid')</label>
                    </div>
                </div>
            </div>
            <div class="input-group col-6 mb-3">
                <div class="custom-file">
                    <label class="custom-file-label" id="result-label" for="result">@lang('translates.placeholders.choose_file')</label>
                    <input type="file" value="{{$data->getAttribute('result')}}" name="result" class="custom-file-input" id="result">
                </div>
            </div>

            @if(!is_null($data->getAttribute('result')))
                <div class="col-6 col p-0">
                    <a class="py-2 d-flex align-items-center list-group-item text-black" href="{{route('order-result.download', $data)}}">
                        <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>
                        @lang('translates.columns.result')
                    </a>
                </div>
            @endif
        </div>

        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
    @php
        $cmrArray = explode(',', $data->getAttribute('cmr'));
        $invoiceArray = explode(',', $data->getAttribute('invoice'));
        $packingArray = explode(',', $data->getAttribute('packing'));
        $otherArray = explode(',', $data->getAttribute('other'));
    @endphp
    <div class="col-md-12 px-0">
        <br>
        <p class="text-muted mb-2">CMR</p>
        <hr class="my-2">
    </div>
    <div class="col-12 px-4">

        @foreach($cmrArray as $cmr)
            <div class="col-12 p-0" style="display: none">
                <form id="download-form{{$cmr}}" action="{{ route('orders.download') }}" method="POST">
                    @csrf
                    <input type="hidden" name="document" value="{{$cmr}}">
                </form>
                <a class="py-2 my-3 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                                    document.getElementById('download-form{{$cmr}}').submit();">
                    {{$cmr}}
                </a>
            </div>

            <form id="download-form{{$cmr}}" action="{{ route('orders.download') }}" method="POST">
                @csrf
                <input type="hidden" name="document" value="{{$cmr}}">
            </form>
            <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                                document.getElementById('download-form{{$cmr}}').submit();">
                <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>

                {{$cmr}}
            </a>
        @endforeach
    </div>
    <div class="col-md-12 px-0">
        <br>
        <p class="text-muted mb-2">Invoice</p>
        <hr class="my-2">
    </div>
    <div class="col-12 col px-4">
        @foreach($invoiceArray as $invoice)

            <form id="download-form{{$invoice}}" action="{{ route('orders.download') }}" method="POST">
                @csrf
                <input type="hidden" name="document" value="{{$invoice}}">
            </form>
            <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                    document.getElementById('download-form{{$invoice}}').submit();">
                <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>

                {{$invoice}}
            </a>
        @endforeach
    </div>
    <div class="col-md-12 px-0">
        <br>
        <p class="text-muted mb-2">Packing</p>
        <hr class="my-2">
    </div>
    <div class="col-12 col px-4">
        @foreach($packingArray as $packing)

            <form id="download-form{{$packing}}" action="{{ route('orders.download') }}" method="POST">
                @csrf
                <input type="hidden" name="document" value="{{$packing}}">
            </form>
            <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                    document.getElementById('download-form{{$packing}}').submit();">
                <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>

                {{$packing}}
            </a>
        @endforeach
    </div>
    <div class="col-md-12 px-0">
        <br>
        <p class="text-muted mb-2">Other</p>
        <hr class="my-2">
    </div>
    <div class="col-12 col px-4">
        @foreach($otherArray as $other)

            <form id="download-form{{$other}}" action="{{ route('orders.download') }}" method="POST">
                @csrf
                <input type="hidden" name="document" value="{{$other}}">
            </form>
            <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                    document.getElementById('download-form{{$other}}').submit();">
                <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>

                {{$other}}
            </a>
        @endforeach
    </div>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
