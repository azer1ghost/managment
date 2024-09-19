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
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        input[type="text"] {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
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
    <form class="col-md-12 form-row px-0" action="{{$action}}" method="POST" enctype="multipart/form-data"
          id="client-form">
        <!-- Main -->
        @csrf @method($method)
        @bind($data)

        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <div class="col-md-12 pl-2 pr-0">
            <p class="text-muted mb-2">@lang('translates.register.progress.personal')</p>
            <hr class="my-2">
            <div class="row pr-2 pl-0">
                @if(request()->has('client_id'))
                    <input type="hidden" name="type" value="{{request()->get('type')}}">
                @endif
                <input type="hidden"
                       name="client_id"
                       value="{{$data->getAttribute('client_id') ?? request()->get('client_id')}}"
                >
                @if($data->client()->exists())
                    <div class="form-group col-12 col-md-4 pr-1">
                        <label for="data-client">@lang('translates.fields.client')</label>
                        <input type="text"
                               class="form-control"
                               id="data-client"
                               value="{{$data->getRelationValue('client')->fullname}}"
                               disabled
                        >
                    </div>
                @endif

                <x-form-group class="pr-3 col-12 col-lg-8" :label="trans('translates.fields.name')" required>
                    <x-form-input name="fullname"/>
                </x-form-group>

                <x-form-group class="pr-3 col-12 col-lg-2" :label="trans('translates.filters.type')">
                    <x-form-select name="type"
                                   :options="[
                                trans('translates.general.legal'),
                                trans('translates.general.physical'),
                                trans('translates.general.foreignphysical'),
                                trans('translates.general.foreignlegal')
                                ]"
                    />
                </x-form-group>
                <div class="form-group col-2">
                    <label for="data-companies">Select Company</label><br/>
                    <select id="data-companies"
                            name="companies[]"
                            multiple
                            required
                            class="filterSelector"
                            data-selected-text-format="count"
                            data-width="fit" title="@lang('translates.filters.select')">
                            @foreach($companies as $company)
                                <option value="{{$company->getAttribute('id')}}"
                                        @foreach($data->companies as $companie)
                                            @if($company->getAttribute('id') == $companie->id)
                                                selected
                                            @endif
                                        @endforeach>
                                    {{$company->getAttribute('name')}}
                                </option>
                            @endforeach
                    </select>
                </div>

                @if(request()->has('client_id') || is_numeric($data->client_id))
                    <x-form-group class="pr-3 col-12 col-lg-4" :label="trans('translates.fields.position')">
                        <x-form-input name="position"/>
                    </x-form-group>
                @endif
                @if(is_null($data->client_id) && !request()->has('client_id'))
                    <div class="form-group col-12 col-md-4">
                        <label for="data-voen">VOEN</label>
                        <input type="text"
                               class="form-control @error('voen') is-invalid @enderror"
                               name="voen"
                               id="data-voen"
                               maxlength="11"
                               placeholder="VOEN/GOOEN"
                               value="{{optional($data)->getAttribute('voen') ?? old('voen')}}"
                               @if(request()->get('type') == $data::LEGAL && optional($data)->getAttribute('type') == $data::LEGAL) @endif
                        >
                        @error('voen')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                @endif
                <x-form-group class="pr-3 col-12 col-md-4" :label="trans('translates.fields.phone2')">
                    <x-form-input name="phone1" required/>
                </x-form-group>

                <div class="form-group col-md-4">
                    <label for="email1">@lang('translates.fields.email1')</label>
                    <input type="text" id="email1" class="form-control" name="email1"
                           value="{{optional($data)->getAttribute('email1')}}"
                           @if(!auth()->user()->hasPermission('satisfactionMeasure-client')) required @endif>
                </div>

                <x-form-group class="pr-3 col-12 col-md-4" :label="trans('translates.fields.address1')">
                    <x-form-input name="address1"/>
                </x-form-group>

                <x-form-group class="pr-3 col-3 col-md-4" :label="trans('translates.fields.sector')">
                    <x-form-input name="sector"/>
                </x-form-group>

                <x-input::date :label="__('translates.fields.birthday')"
                               name="birthday"
                               :value="optional($data)->getAttribute('birthday')"
                               width="4"
                               class="pr-0"
                />

                <x-form-group class="pr-3 col-12 col-12" :label="trans('translates.fields.detail')">
                    <x-form-textarea name="detail" style="height: 200px"/>
                </x-form-group>

                <div class="form-group col-6 user">
                    <label for="user_id">Vasitəçi</label><br/>
                    <select class="select2 form-control" name="reference_id" id="user_id">
                        <option value="">Birbaşa</option>
                        @foreach($users as $user)
                            <option @if($engagement !== null) @if($engagement->getAttribute('user_id') == $user->id) selected
                                    @endif @endif value="{{$user->id}}">{{$user->getFullNameWithPositionAttribute()}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-3" wire:ignore>
                    <label for="data-channel">Kanal @lang('translates.placeholders.choose')</label>
                    <select name="channel" id="data-channel" class="form-control">
                        <option value="0">Kanal @lang('translates.placeholders.choose')</option>
                        @foreach($channels as $key => $channel)
                            <option @if(optional($data)->getAttribute('channel') == $channel ) selected
                                    @endif value="{{$channel}}">
                                @lang('translates.client_channels.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row col-md-12">
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">Document</p>
                <hr class="my-2">
            </div>
        </div>
        <div class="input-group col-12 mb-3">
            <div class="custom-file">
                <label class="custom-file-label" id="protocol-label" for="protocol">Qiymət Təklifi</label>
                <input type="file" value="{{$data->getAttribute('protocol')}}" name="protocol" class="custom-file-input"
                       id="protocol">
            </div>
        </div>
        <div class="input-group col-6 ml-2">
            <div class="custom-control custom-switch mr-3 col-6">
                <input type="checkbox" name="send_sms" class="custom-control-input" id="send_sms"
                       @if($data->getAttribute('send_sms') || $method == 'POST' ) checked @endif>
                <label class="custom-control-label" for="send_sms">@lang('translates.buttons.send_sms')</label>
            </div>
            <div class="custom-control custom-switch mr-5 col-6">
                <input type="checkbox" name="active" class="custom-control-input" id="active"
                       @if($data->getAttribute('active') || $method == 'POST' ) checked @endif>
                <label class="custom-control-label" for="active">@lang('translates.buttons.active')</label>
            </div>
        </div>

        @if(!is_null($data->getAttribute('protocol')))
            <div class="card-body col-12 col p-0">
                <a class="py-2 d-flex align-items-center list-group-item text-black"
                   href="{{route('protocol.download', $data)}}">
                    <i style="font-size: 20px" class="fas fa-file fa-3x mr-2"></i>
                    <span>{{$data->getAttribute('document_type')}} Qiymət protokolu</span>
                </a>
            </div>
        @endif
        <div class="form-group col-12 col-md-3">
            <label for="data-payment_method">@lang('translates.general.payment_method')</label>
            <select name="payment_method" id="data-payment_method" class="form-control" @if($data->getAttribute('user_id') != [187,95]) required @endif>
                <option disabled>@lang('translates.general.payment_method')</option>
                @foreach($payment_methods as $key => $payment_method)
                    <option @if($key == $data->payment_method) selected @endif
                    value="{{$key}}">
                        @lang('translates.payment_methods.' . $key)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <table>
                <tr>
                    <th>Xidmətlər</th>
                    <th>Qiymət</th>
                </tr>
                <tr>
                    <td><label>Əsas Vərəq</label></td>
                    <td>
                        <input type="text" class="form-control" name="main_paper" value="{{ $data->getAttribute('main_paper') }}" @if($data->getAttribute('user_id') != [187, 95]) required @endif>
                    </td>
                </tr>
                <tr>
                    <td><label>QIB Əsas Vərəq</label></td>
                    <td>
                        <input type="text" class="form-control" name="qibmain_paper" value="{{ $data->getAttribute('qibmain_paper') }}" @if($data->getAttribute('user_id') != [187,95]) required @endif>
                    </td>
                </tr>
                @if($method !== 'POST')
                    @foreach ($services as $service)
                        <tr>
                            <input type="hidden" name="services[{{ $service->getAttribute('id') }}][client_id]" value="{{ $data->id }}">
                            <input type="hidden" name="services[{{ $service->getAttribute('id') }}][service_id]" value="{{ $service->getAttribute('id') }}">
                            <td><label>{{ $service->getAttribute('name') }}</label></td>
                            <td>
                                <input type="text" class="form-control service-input" data-service-id="{{ $service->getAttribute('id') }}" name="services[{{ $service->getAttribute('id') }}][amount]" value="{{ $service->pivot->amount ?? '' }}" @if(in_array($service->getAttribute('id'), [1, 2, 5, 17]) && $data->getAttribute('user_id') != [187,95]) required @endif>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>


    @if($action)
            <x-input::submit/>
        @endif
        @endbind
    </form>

    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.files.necessary')"/>
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
                                <a href="{{route('clients.destroy', $client)}}" delete
                                   data-name="{{$client->getAttribute('fullname')}}"
                                   class="btn btn-sm btn-outline-danger">
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
                            <div class="col-7 alert alert-danger text-center"
                                 role="alert">@lang('translates.general.empty')</div>
                        </div>
                    </th>
                </tr>
            @endforelse
            </tbody>
        </table>
        @if($method == 'PUT')
            <a href="{{route('clients.create', ['type' => $data::PHYSICAL, 'client_id' => $data->getAttribute('id')])}}"
               class="btn btn-outline-primary col-md-12 p-2">@lang('translates.clients.add_representative')</a>
        @endif

    @endif
@endsection
@section('scripts')
    <script>
        $('#protocol').change(function (e) {
            let fileName = (e.target.files.length > 0) ? e.target.files[0].name : '@lang('translates.placeholders.choose_file')';
            $('#protocol-label').text(fileName);
        });
        @if(is_null($action))
        $('#client-form :input').attr('disabled', true)
        @endif

        @if(!auth()->user()->hasPermission('canUploadContract-client'))
        $('#document-form :input').attr('disabled', true)
        @endif

        @if($method == 'PUT' || request()->has('client_id'))
        $('#data-type').attr('disabled', true);
        @else
        $('#data-type').change(function () {
            window.location.href = '{{route('clients.create')}}' + '?type=' + $(this).val();
        });
        @endif
    </script>
    <script>
        $(document).ready(function() {
            // Hizmet ID eşleşmelerini tanımlayın
            const serviceMappings = {
                1: [17, 18, 19, 23, 20, 21, 30, 22, 16, 26, 27, 29, 24, 25],
                9: [3, 4, 10, 11, 12],
                6: [31, 32, 33, 34, 35, 36, 37, 38],
                8: [7, 58]
            };

            // Değer değişimlerini dinle
            $('.service-input').on('input', function() {
                const serviceId = $(this).data('service-id');
                const value = $(this).val();

                // Eğer bu ID'nin eşleşmesi varsa
                if (serviceMappings[serviceId]) {
                    const mappedServiceIds = serviceMappings[serviceId];
                    // Eşleşen ID'lere sahip olan inputları bulun ve değerlerini değiştirin
                    mappedServiceIds.forEach(function(mappedServiceId) {
                        $(`.service-input[data-service-id=${mappedServiceId}]`).val(value);
                    });
                }
            });
        });

    </script>
@endsection
