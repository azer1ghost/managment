@extends('layouts.main')

@section('title', trans('translates.navbar.iso_document'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('necessaries.index')">
            @lang('translates.navbar.necessary')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Adı" placeholder="Sənəd adı daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-textarea name="detail" label="Detaylar" placeholder="Detayları daxil edin">{{$data->getAttribute('detail')}}</x-form-textarea>
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

