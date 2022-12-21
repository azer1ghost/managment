@extends('layouts.main')

@section('title', __('translates.navbar.client'))
@section('style')
    <style>
        .customInput {
            outline: 0;
            border-width: 0 0 2px;
            border-color: blue;
            width: 50px
        }
        .customInput:focus {
            border-color: green
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('clients.index')">
            @lang('translates.navbar.client')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('fullname')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form class="col-md-12 form-row px-0" action="{{$action}}" method="POST" enctype="multipart/form-data" id="client-form">
        <!-- Main -->
        @csrf @method($method)
        @bind($data)

        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <div class="col-md-12 pl-2 pr-0">
            <p class="text-muted mb-2">@lang('translates.register.progress.personal')</p>
            <hr class="my-2">
            <div class="row pr-2 pl-0">
                <input type="hidden" name="client_id" value="{{$data->getAttribute('client_id') ?? request()->get('client_id')}}">
                @if(request()->has('client_id'))
                    <input type="hidden" name="type" value="{{request()->get('type')}}">
                @endif
                @if($data->client()->exists())
                    <div class="form-group col-12 col-md-4 pr-1">
                        <label for="data-client">@lang('translates.fields.client')</label>
                        <input type="text" class="form-control" id="data-client" value="{{$data->getRelationValue('client')->fullname}}" disabled>
                    </div>
                @endif
                <x-form-group  class="pr-3 col-12 col-lg-4" :label="trans('translates.filters.type')">
                    <x-form-select name="type" :options="[trans('translates.general.legal'), trans('translates.general.physical')]" />
                </x-form-group>
                <div class="form-group">
                    <label for="data-companies">@lang('translates.clients.selectCompany')</label><br/>
                    <select id="data-companies" name="companies[]"  required class="form-control" title="@lang('translates.filters.select')">
                        <option value="">@lang('translates.clients.selectCompany')</option>

                    @foreach($companies as $company)
                            <option @foreach($data->companies as $companie) @if($company->getAttribute('id') == $companie->id) selected @endif @endforeach value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                </div>
                <x-form-group  class="pr-3 col-12 col-lg-8" :label="trans('translates.fields.name')" required>
                    <x-form-input name="fullname" />
                </x-form-group>
                @if (request()->get('type') == $data::PHYSICAL || $data->getAttribute('type') == $data::PHYSICAL)
                    <x-form-group  class="pr-3 col-12 col-lg-4" :label="trans('translates.fields.father')">
                        <x-form-input name="father" />
                    </x-form-group>
                @endif
                <x-form-group  class="pr-3 col-12 col-lg-8" :label="trans('translates.fields.detail')">
                    <x-form-textarea name="detail" style="height: 300px" />
                </x-form-group>
                @if (request()->get('type') == $data::LEGAL && optional($data)->getAttribute('type') == $data::LEGAL)
                    <x-input::date  :label="__('translates.fields.created_at')" name="celebrate_at" :value="optional($data)->getAttribute('celebrate_at')" width="4" class="pr-0" />
                @endif
                    <x-input::date  :label="__('translates.fields.birthday')" name="birthday" :value="optional($data)->getAttribute('birthday')" width="4" class="pr-0" />
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2"> @lang('translates.fields.employment')</p>
            <hr class="my-2">
            <div class="row">
                @if(request()->has('client_id') || is_numeric($data->client_id))
                    <x-form-group  class="pr-3 col-12 col-lg-4" :label="trans('translates.fields.position')">
                        <x-form-input name="position" />
                    </x-form-group>
                @endif
                @if(is_null($data->client_id) && !request()->has('client_id'))
                    <div class="form-group col-12 col-md-4 pr-1">
                        <label for="data-voen">VOEN</label>
                        <input type="text"
                                class="form-control @error('voen') is-invalid @enderror"
                                name="voen"
                                id="data-voen"
                                maxlength="11"
                                placeholder="VOEN/GOOEN"
                               value="{{optional($data)->getAttribute('voen') ?? old('voen')}}"
                               @if(request()->get('type') == $data::LEGAL && optional($data)->getAttribute('type') == $data::LEGAL) required @endif
                        >
                        @error('voen')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif
            </div>
        </div>
        <div class="form-row col-md-12">
            <!-- Passport -->
            @if (request()->get('type') == $data::PHYSICAL || $data->getAttribute('type') == $data::PHYSICAL)
                <div class="col-md-12">
                    <br>
                    <p class="text-muted mb-2">@lang('translates.fields.passport')</p>
                    <hr class="my-2">
                </div>
                <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.serial')">
                    <x-form-select name="serial_pattern" :options="['AA' => 'AA','AZE' => 'AZE']" />
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.position')" >
                    <x-form-input name="serial" :placeholder="__('translates.placeholders.serial_pattern')"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-3"  >
                    <x-form-input name="fin" :placeholder="__('translates.placeholders.fin')" label="fin"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.gender')">
                    <x-form-select name="gender" :options="[__('translates.gender.male'),__('translates.gender.female')]" />
                </x-form-group>
            @endif
            <!-- Contact -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.contact')</p>
                <hr class="my-2">
            </div>
            <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.phone1')">
                <x-form-input name="phone1" required/>
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.phone2')" >
                <x-form-input name="phone2"/>
            </x-form-group>

            <div class="form-group col-md-3">
                <label for="email1">@lang('translates.fields.email1')</label>
                <input type="email" id="email1" class="form-control" name="email1" value="{{optional($data)->getAttribute('email1')}}" @if(auth()->id() !== 103 || auth()->id() !== 123 || auth()->id() !== 124) required @endif>
            </div>

            <x-form-group  class="pr-3 col-12 col-lg-3" :label="trans('translates.fields.email2')" >
                <x-form-input type="email" name="email2"/>
            </x-form-group>

            <!-- Address -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.address')</p>
                <hr class="my-2">
            </div>
            <x-form-group  class="pr-3 col-12 col-lg-6" :label="trans('translates.fields.address1')" >
                <x-form-input name="address1"/>
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6" :label="trans('translates.fields.address2')" >
                <x-form-input name="address2"/>
            </x-form-group>
            @if(auth()->user()->hasPermission('satisfactionMeasure-client') )
                <div class="form-group col-12 col-md-3">
                    <label for="data-satisfaction">@lang('translates.general.satisfaction')</label>
                    <select name="satisfaction" id="data-satisfaction" class="form-control">
                        <option disabled >@lang('translates.general.satisfaction')</option>
                        @foreach($satisfactions as $key => $satisfaction)
                            <option
                                    @if(optional($data)->getAttribute('satisfaction') === $satisfaction ) selected @endif
                            value="{{$satisfaction}}">
                                @lang('translates.satisfactions.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

        </div>
{{--        <div class="input-group mb-3">--}}
{{--            <div class="custom-file">--}}
{{--                <input type="file" name="protocol" class="custom-file-input" id="protocol">--}}
{{--                <label class="custom-file-label" for="protocol" aria-describedby="protocol2">@lang('translates.placeholders.choose_file')</label>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="card-body p-0">--}}
{{--            <a class="col-12 py-2 d-flex align-items-center list-group-item" href="{{ route('protocol.download', $data) }}">--}}
{{--                <i style="font-size: 70px" class="fa fa-file-pdf fa-3x mr-2"></i>--}}
{{--                <span>{{$data->getAttribute('protocol')}}</span>--}}
{{--            </a>--}}
{{--        </div>--}}


        @php
            $price = $data->getAttribute('price');
            preg_match('/(?<=EGB = )(.*)(?= AZN, Q)/', $price, $egb);
            preg_match('/(?<=QIB = )(.*)(?= AZN, Temsilcilik)/', $price, $qib);
            preg_match('/(?<=Temsilcilik = )(.*)(?= AZN, CMR)/', $price, $t);
            preg_match('/(?<=CMR = )(.*)(?= AZN, TGB)/', $price, $cmr);
            preg_match('/(?<=TGB = )(.*)(?= AZN, SB)/', $price, $tgb);
            preg_match('/(?<=SB = )(.*)(?= AZN, Tircarnet)/', $price, $sb);
            preg_match('/(?<=Tircarnet = )(.*)(?= AZN)/', $price, $tircarnet);
        @endphp
        <div class="col-12">
           <p>
               Elektron Gömrük Bəyannaməsi
               <input aria-label="egb" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $egb[1]}}" name="egb" type="number" />
                AZN,
               <br>
               Qısa İdxal Bəyannaməsi
               <input aria-label="qib" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $qib[1]}}" name="qib" type="number" />
                AZN,
               <br>
               Təmsilçilik
               <input aria-label="t" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $t[1]}}" name="t" type="number" />
                AZN,
               <br>
               CMR
               <input aria-label="cmr" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $cmr[1]}}" name="cmr" type="number" />
                AZN,
               <br>
               Tranzit Gömrük Bəyannaməsi
               <input aria-label="tgb" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $tgb[1]}}" name="tgb" type="number" />
               AZN,
               <br>
               Sadələşdirilmiş Bəyannamə
               <input aria-label="sb" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $sb[1]}}" name="sb" type="number" />
                AZN,
               <br>
               TİRCARNET
               <input aria-label="tircarnet" class="customInput" value="{{$data->getAttribute('price') == null ? 0 : $tircarnet[0]}}" name="tircarnet" type="number" />
               AZN

           </p>
        </div>

        @if($action)
            <x-input::submit/>
        @endif
        @endbind
    </form>

    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.files.contract')" />
            <x-document-upload :id="$data->id" model="Client"/>
        </div>
    @endif

    @if($method != 'POST' && is_null($data->client_id) && $data->getAttribute('type') == $data::LEGAL)
        <table class="table table-responsive-sm mt-4">
            <thead>
            <tr>
                <th title="@lang('translates.general.physical_client_name')">@lang('translates.columns.full_name')</th>
                <th title="@lang('translates.general.physical_client_mail')">@lang('translates.columns.email')</th>
                <th title="@lang('translates.general.physical_client_phone')">@lang('translates.fields.phone')</th>
                <th title="@lang('translates.general.physical_client_position')">@lang('translates.fields.position')</th>
                <th>@lang('translates.columns.actions')</th>
            </tr>
            </thead>
            <tbody>
            @forelse($data->getRelationValue('clients') as $client)
                <tr>
                    <td>{{$client->getAttribute('fullname')}}</td>
                    <td>{{$client->getAttribute('email1')}}</td>
                    <td>{{$client->getAttribute('phone1')}}</td>
                    <td>{{$client->getAttribute('position')}}</td>
                    <td>
                        @if($method == 'PUT')
                            <div class="btn-sm-group">
                                <a href="{{route('clients.show', $client)}}" class="btn btn-sm btn-outline-primary">
                                    <i class="fal fa-eye"></i>
                                </a>
                                <a href="{{route('clients.edit', $client)}}" class="btn btn-sm btn-outline-success">
                                    <i class="fal fa-pen"></i>
                                </a>
                                <a href="{{route('clients.destroy', $client)}}" delete data-name="{{$client->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger">
                                    <i class="fal fa-trash"></i>
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <th colspan="5">
                            <div class="row justify-content-center m-3">
                                <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                            </div>
                        </th>
                    </tr>
            @endforelse

            </tbody>
        </table>
        @if($method == 'PUT')
            <a href="{{route('clients.create', ['type' => $data::PHYSICAL, 'client_id' => $data->getAttribute('id')])}}" class="btn btn-outline-primary col-md-12 p-2">@lang('translates.clients.add_representative')</a>
        @endif

    @endif
@endsection
@section('scripts')
    <script>
        @if(is_null($action))
            $('#client-form :input').attr('disabled', true)
        @endif

        @if(!auth()->user()->hasPermission('canUploadContract-client'))
            $('#document-form :input').attr('disabled', true)
        @endif

        @if($method == 'PUT' || request()->has('client_id'))
            $('#data-type').attr('disabled', true);
        @else
            $('#data-type').change(function (){
                window.location.href = '{{route('clients.create')}}' + '?type=' + $(this).val();
            });
        @endif
    </script>
@endsection
