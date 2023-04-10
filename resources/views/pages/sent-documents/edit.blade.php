@extends('layouts.main')

@section('title', trans('translates.navbar.sent_document'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('sent-documents.index')">
            MB-P-023/03 @lang('translates.navbar.sent_document')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('overhead_num')}}
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
                        <x-form-input  name="overhead_num" label="Sənədin Qaimə nömrəsi" placeholder="Qaimə Nömrəsi daxil edin"/>
                    </x-form-group>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-sent_date">Göndərilmə Tarixi</label>
                        <input type="datetime-local" name="sent_date"
                               value="{{optional($data->getAttribute('sent_date'))->format('Y-m-d')}}" id="data-sent_date" class="form-control">
                    </div>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="organization" label="Təşkilat adı" placeholder="Təşkilat adı daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="content" label="Sənədin Məzmunu" placeholder="Sənədin Məzmununu daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="note" label="Qeyd" placeholder="Qeyd daxil edin"/>
                    </x-form-group>
                    <div class="form-group">
                        <label for="data-companies">@lang('translates.clients.selectCompany')</label><br/>
                        <select id="data-companies" name="company_id"  required class="form-control" title="@lang('translates.filters.select')">
                            <option value="">@lang('translates.clients.selectCompany')</option>
                            @foreach($companies as $company)
                                <option
                                        value="{{$company->getAttribute('id')}}" @if($data->getAttribute('company_id') == $company->id) selected @endif>{{$company->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
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
@endsection
