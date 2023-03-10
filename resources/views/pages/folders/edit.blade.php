@extends('layouts.main')

@section('title', trans('translates.navbar.folder'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('folders.index')">
            @lang('translates.navbar.folder')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
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
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Qovluq adı" placeholder="Qovluq adı daxil edin"/>
                    </x-form-group>
                </div>
            </div>
        </div>
        <div class="form-group col-4">
            <label for="data-companies">@lang('translates.clients.selectCompany')</label><br/>
            <select id="data-companies" name="company_id"  required class="form-control" title="@lang('translates.filters.select')">
                <option value="">@lang('translates.clients.selectCompany')</option>
                @foreach($companies as $company)
                    <option
                            value="{{$company->getAttribute('id')}}" @if($data->getAttribute('company_id') == $company->id) selected @endif>{{$company->getAttribute('name')}}</option>
                @endforeach
            </select>
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
