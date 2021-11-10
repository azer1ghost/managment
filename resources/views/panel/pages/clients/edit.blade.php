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
            @if (!is_null($data))
                {{optional($data)->getAttribute('fullname')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form class="col-md-12 form-row px-0" action="{{$action}}" method="post" enctype="multipart/form-data">
        @csrf @method($method)
        <!-- Main -->
        <div class="col-md-12 pl-2 pr-0">
            <p class="text-muted mb-2">@lang('translates.register.progress.personal')</p>
            <hr class="my-2">
            <div class="row pr-2 pl-0">
                <x-input::text  name="name"    :value="optional($data)->getAttribute('name')"    width="4" class="pr-1" :label="__('translates.fields.name')" required=""/>
                <x-input::text  name="surname" :value="optional($data)->getAttribute('surname')" width="4" class="pr-1" :label="__('translates.fields.surname')" />
                <x-input::text  name="father"  :value="optional($data)->getAttribute('father')"  width="4" class="pr-1" :label="__('translates.fields.father')" />
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2"> @lang('translates.fields.employment')</p>
            <hr class="my-2">
            <div class="row">
                <div class="form-group col-12 col-md-2">
                    <label for="data-gender">{{__('translates.fields.company')}}</label>
                    <select class="form-control" name="company_id" id="data-gender" style="padding: .375rem 0.75rem !important;">
                        <option disabled selected value="">{{__('translates.fields.company')}} {{__('translates.placeholders.choose')}}</option>
                        @foreach($companies as $company)
                            <option @if ($company->id === optional($data)->getAttribute('company_id')) selected @endif value="{{$company->id}}">
                                {{$company->name}}
                            </option>
                        @endforeach
                    </select>
                    @error('gender')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
                <x-input::text name="position"  :value="optional($data)->getAttribute('position')" :label="__('translates.fields.position')" width="4" class="pr-1" />
                <x-input::text name="voen"     :value="optional($data)->getAttribute('voen')"     width="4" class="pr-1"  label="VOEN/GOOEN" />
            </div>
        </div>
        <div class="form-row col-md-12">
            <!-- Passport -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.passport')</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="serial_pattern" :value="optional($data)->getAttribute('serial_pattern')" :label="__('translates.fields.serial')" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
            <x-input::text    name="serial"         :value="optional($data)->getAttribute('serial')"   label=" "   width="2" class="pr-1"  :placeholder="__('translates.placeholders.serial_pattern')"/>
            <x-input::text    name="fin"            :value="optional($data)->getAttribute('fin')"       width="2" class="pr-1"  :placeholder="__('translates.placeholders.fin')"/>
            <x-input::select  name="gender"         :value="optional($data)->getAttribute('gender')"   :options="[__('translates.gender.male'),__('translates.gender.female')]" :label="__('translates.fields.gender')" width="2" class="pr-1" />
            <!-- Contact -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.contact')</p>
                <hr class="my-2">
            </div>
            <x-input::text   name="phone_coop" :value="optional($data)->getAttribute('phone_coop')"   :label="__('translates.fields.phone_coop')"  width="3" class="pr-1" />
            <x-input::text   name="phone"      :value="optional($data)->getAttribute('phone')"        :label="__('translates.fields.phone_private')"   width="3" class="pr-1" />
            <x-input::email  name="email_coop" :value="optional($data)->getAttribute('email_coop')"  :label="__('translates.fields.email_coop')"  width="3" class="pr-1" />
            <x-input::email  name="email"      :value="optional($data)->getAttribute('email')"       :label="__('translates.fields.email_private')"    width="3" class="pr-1"  required=""/>
            <!-- Address -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.address')</p>
                <hr class="my-2">
            </div>
            <x-input::text    name="address"        :value="optional($data)->getAttribute('address')"       :label="__('translates.fields.address')"   width="6" class="pr-1" />
            <x-input::text    name="address_coop"   :value="optional($data)->getAttribute('address_coop')"  :label="__('translates.fields.address_coop')" width="6" class="pr-1"  />
            @if($action)
                <x-input::submit/>
            @endif
        </div>
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
