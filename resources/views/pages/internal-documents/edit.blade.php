@extends('layouts.main')

@section('title', trans('translates.navbar.internal_document'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('internal-documents.index')">
            @lang('translates.navbar.internal_document')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('document_name')}}
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
                    <div class="form-group col-6 user">
                        <label for="department_id">@lang('translates.columns.department')</label><br/>
                        <select class="select2 form-control" name="department_id" id="department_id">
                            <option value="">@lang('translates.general.department_select')</option>
                            @foreach($departments as $department)
                                <option @if($data->getAttribute('department_id') == $department->id) selected @endif value="{{$department->id}}">{{$department->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="document_name" label="Sənəd adı" placeholder="Sənəd adı daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="document_code" label="Kod" placeholder="Kod daxil edin"/>
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
@endsection
