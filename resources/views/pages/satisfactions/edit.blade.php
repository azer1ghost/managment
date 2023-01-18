@extends('layouts.main')

@section('title', trans('translates.navbar.satisfaction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('satisfactions.index')">
            @lang('translates.navbar.satisfaction')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{optional($data)->getAttribute('url')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
            <div class=" row mt-4">
                <div class="form-group col-12">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="data-company_id">Company Select</label>
                            <select name="company_id" id="data-company_id" class="form-control">
                                <option value="" selected>Company Select</option>
                                @foreach($companies as $company)
                                    <option @if(optional($data)->getAttribute('company_id') === $company->getAttribute('id') ) selected
                                            @endif value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-6">
                            <label for="url">Url</label>
                            <input type="text" id="url" name="url" class="form-control" placeholder="@lang('translates.general.url')" value="{{$data->getAttribute('url')}}">
                        </div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" @if($data->getAttribute('is_active')) checked @endif>
                        <label class="custom-control-label" for="is_active">@lang('translates.users.statuses.active')</label>
                    </div>
                </div>
            </div>
            @if($method !== 'POST' && auth()->user()->isDeveloper())
                <livewire:satisfaction-parameter :data="$data" :action="$action"/>
            @endif
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
