@extends('layouts.main')

@section('title', trans('translates.navbar.fund'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('funds.index')">
             Bank və Kodlar
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{optional($data)->getAttribute('executor')}}
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

                    <div class="form-group col-6">
                        <label for="company_id">@lang('translates.fields.company')</label><br/>
                        <select class="form-control" name="company_id" id="company_id" data-width="fit">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($companies as $company)
                                <option @if($data->getAttribute('company_id') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="user_id">@lang('translates.fields.director')</label><br/>
                        <select class="form-control" name="user_id" id="user_id" data-width="fit">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->fullname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="voen" label="Yer" placeholder="VOEN Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="main_activity" label="Əsas Fəaliyyət" placeholder="Əsas Fəaliyyət daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="asan_imza" label="Asan İmza" placeholder="Asan İmza daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="code" label="Kod" placeholder="Kod daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="adress" label="Qeydiyyat Ünvanı" placeholder="Qeydiyyat Ünvanı daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="voen_code" label="VÖEN Kodu" placeholder="VÖEN Kodu daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="voen_pass" label="VÖEN Parol" placeholder="VÖEN parolu daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="pass" label="Şifrə" placeholder="Şifrəni daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="respublika_code" label="BANK RESPUBLIKA ID" placeholder="BANK RESPUBLIKA ID daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="respublika_pass" label="BANK RESPUBLIKA KOD" placeholder="BANK RESPUBLIKA KOD daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="kapital_code" label="KAPİTAL BANK ID" placeholder="KAPİTAL BANK ID daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input name="kapital_pass" label="KAPİTAL BANK KOD" placeholder="KAPİTAL BANK KOD daxil edin"/>
                    </x-form-group>

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
    <script>
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    </script>
@endsection
