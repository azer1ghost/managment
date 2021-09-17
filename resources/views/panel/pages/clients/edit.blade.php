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
            <p class="text-muted mb-2">PERSONAL</p>
            <hr class="my-2">
            <div class="row pr-2 pl-0">
                <x-input::text  name="name"    :value="optional($data)->getAttribute('name')"    width="4" class="pr-1" required=""/>
                <x-input::text  name="surname" :value="optional($data)->getAttribute('surname')" width="4" class="pr-1" />
                <x-input::text  name="father"  :value="optional($data)->getAttribute('father')"  width="4" class="pr-1" label="Father's name" />
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2">EMPLOYMENT</p>
            <hr class="my-2">
            <div class="row">
                <x-input::text name="company"  :value="optional($data)->getAttribute('company')"  width="4" class="pr-1"  label="Company" />
                <x-input::text name="voen"     :value="optional($data)->getAttribute('voen')"     width="4" class="pr-1"  label="VOEN" />
            </div>
        </div>
        <div class="form-row col-md-12">
            <!-- Passport -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">PASSPORT</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="serial_pattern" :value="optional($data)->getAttribute('serial_pattern')" label="Serial" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
            <x-input::text    name="serial"         :value="optional($data)->getAttribute('serial')"   label=" "   width="2" class="pr-1"  placeholder="Enter serial number"/>
            <x-input::text    name="fin"            :value="optional($data)->getAttribute('fin')"       width="2" class="pr-1"  placeholder="Enter fin"/>
            <x-input::select  name="gender"         :value="optional($data)->getAttribute('gender')"   :options="['male','female']" width="2" class="pr-1" />
            <!-- Contact -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">CONTACT</p>
                <hr class="my-2">
            </div>
            <x-input::text   name="phone_coop" :value="optional($data)->getAttribute('phone_coop')"   label="Cooperative number" width="3" class="pr-1" />
            <x-input::text   name="phone"      :value="optional($data)->getAttribute('phone')"        label="Personal number"    width="3" class="pr-1" />
            <x-input::email  name="email_coop" :value="optional($data)->getAttribute('email_coop')"   label="Cooperative Email"  width="3" class="pr-1" />
            <x-input::email  name="email"      :value="optional($data)->getAttribute('email')"        label="Personal Email"     width="3" class="pr-1"  required=""/>
            <!-- Address -->
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">ADDRESS</p>
                <hr class="my-2">
            </div>
            <x-input::text    name="address"        :value="optional($data)->getAttribute('address')"       width="6" class="pr-1" />
            <x-input::text    name="address_coop"   :value="optional($data)->getAttribute('address_coop')"  width="6" class="pr-1"  label="Cooperative Address" />
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
