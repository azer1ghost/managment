@extends('views.layouts.main')

@section('title', trans('translates.navbar.changes'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('changes.index')">
            @lang('translates.navbar.changes')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('id')}}
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
                        <x-form-input name="name" label="İnspektor Adı"
                                      placeholder="İnspektor Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input name="phone" label="Telefon"
                                         placeholder="Telefon Nömrəsi daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-textarea name="return_reason" label="Geri qayıtma səbəbi"
                                         placeholder="Geri qayıtma səbəbini daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-textarea name="main_reason" label="Əsas Səbəb"
                                         placeholder="Əsas səbəbini daxil edin"/>
                    </x-form-group>
                </div>
            </div>
        </div>

        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
{{--    @if($method != 'POST')--}}
{{--        <div class="my-5">--}}
{{--            <x-documents :documents="$data->documents" :title="trans('translates.navbar.document')"/>--}}
{{--            <x-document-upload :id="$data->id" model="Change"/>--}}
{{--        </div>--}}
{{--    @endif--}}
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

@endsection
