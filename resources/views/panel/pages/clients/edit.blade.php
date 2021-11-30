@extends('layouts.main')

@section('title', __('translates.navbar.client'))

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
    <form class="col-md-12 form-row px-0" action="{{$action}}" method="POST" enctype="multipart/form-data">
        <!-- Main -->
        @csrf @method($method)
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
                        <label for="data-company">Company</label>
                        <input type="text" class="form-control" id="data-company" value="{{$data->getRelationValue('client')->fullname}}" disabled>
                    </div>
                @endif
                <x-input::select name="type"      :value="$data->getAttribute('type') ?? request()->get('type')"  width="4" class="pr-1" :options="[trans('translates.general.legal'), trans('translates.general.physical')]"/>
                <x-input::text   name="fullname"  :value="$data->getAttribute('fullname')"    width="4" class="pr-1" :label="__('translates.fields.name')" required=""/>
                @if (request()->get('type') == $data::PHYSICAL || $data->getAttribute('type') == $data::PHYSICAL)
                    <x-input::text  name="father"   :value="$data->getAttribute('father')"  width="4" class="pr-1" :label="__('translates.fields.father')" />
                @endif
                <x-input::textarea name="detail" :value="optional($data)->getAttribute('detail')" :label="trans('translates.fields.detail')" width="4" class="pr-3"/>
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2"> @lang('translates.fields.employment')</p>
            <hr class="my-2">
            <div class="row">
                @if(request()->has('client_id') || !is_null($data->client_id))
                    <x-input::text name="position"  :value="$data->getAttribute('position')"     width="4" class="pr-1"  :label="trans('translates.fields.position')" />
                @endif
                @if(!request()->has('client_id') || is_null($data->client_id))
                    <x-input::text name="voen"  :value="$data->getAttribute('voen')"     width="4" class="pr-1"  label="VOEN/GOOEN" />
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
                <x-input::select  name="serial_pattern" :value="$data->getAttribute('serial_pattern')" :label="__('translates.fields.serial')" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
                <x-input::text    name="serial"         :value="$data->getAttribute('serial')"   label=" "   width="2" class="pr-1"  :placeholder="__('translates.placeholders.serial_pattern')"/>
                <x-input::text    name="fin"            :value="$data->getAttribute('fin')"       width="2" class="pr-1"  :placeholder="__('translates.placeholders.fin')"/>
                <x-input::select  name="gender"         :value="$data->getAttribute('gender')"   :options="[__('translates.gender.male'),__('translates.gender.female')]" :label="__('translates.fields.gender')" width="2" class="pr-1" />
            @endif
            <!-- Contact -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.contact')</p>
                <hr class="my-2">
            </div>
            <x-input::text   name="phone1"      :value="$data->getAttribute('phone1')"        :label="__('translates.fields.phone1')"   width="3" class="pr-1" />
            <x-input::text   name="phone2"      :value="$data->getAttribute('phone2')"   :label="__('translates.fields.phone2')"  width="3" class="pr-1" />
            <x-input::email  name="email1"      :value="$data->getAttribute('email1')"       :label="__('translates.fields.email1')"    width="3" class="pr-1"  required=""/>
            <x-input::email  name="email2"      :value="$data->getAttribute('email2')"  :label="__('translates.fields.email2')"  width="3" class="pr-1" />
            <!-- Address -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.address')</p>
                <hr class="my-2">
            </div>
            <x-input::text    name="address1"   :value="$data->getAttribute('address1')"       :label="__('translates.fields.address1')"   width="6" class="pr-1" />
            <x-input::text    name="address2"   :value="$data->getAttribute('address2')"  :label="__('translates.fields.address2')" width="6" class="pr-1"  />
        </div>
        @if($action)
            <x-input::submit/>
        @endif
    </form>

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
            $('form :input').attr('disabled', true)
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
