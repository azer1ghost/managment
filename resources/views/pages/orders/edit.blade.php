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
                    <div class="col-12 col-md-6 pr-3">
                        <label for="department_id">Update department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="" selected disabled>Select department</option>
                            @foreach($users as $user)
                                <option @if(optional($data)->getAttribute('user_id') === $user->getAttribute('id')) selected
                                        @endif value="{{$user->getAttribute('id')}}">{{$user->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $cmrArray = explode(',', $data->getAttribute('cmr'));
                        $invoiceArray = explode(',', $data->getAttribute('invoice'));
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
                                <input type="hidden" name="cmr" value="{{$cmr}}">
                            </form>
                            <a class="py-2 my-3 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                                    document.getElementById('download-form{{$cmr}}').submit();">
                                {{$cmr}}
                            </a>
                        </div>

                        <form id="download-form{{$cmr}}" action="{{ route('orders.download') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cmr" value="{{$cmr}}">
                        </form>
                        <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                                document.getElementById('download-form{{$cmr}}').submit();">
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
                                <input type="hidden" name="cmr" value="{{$invoice}}">
                            </form>
                            <a class="py-2 my-2 d-flex align-items-center list-group-item text-black" onclick="event.preventDefault();
                                    document.getElementById('download-form{{$invoice}}').submit();">
                                {{$invoice}}
                            </a>
                    @endforeach
                    </div>

                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
