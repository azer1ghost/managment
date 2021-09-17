@extends('layouts.main')

@section('title', __('translates.navbar.option'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('options.index')">
            @lang('translates.navbar.option')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('text')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content form-row mt-4" >
            <x-input::text  name="text"  :value="optional($data)->getAttribute('text')"  width="4"  class="pr-3" />
            <div class="col-12 py-2">
                <label for="parameterFilter">Parameters</label><br/>
                <select name="parameters[]" id="parameterFilter" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                    @foreach($parameters as $key => $parameter)
                        <option @if(optional(optional($data)->parameters())->exists() && $data->getRelationValue('parameters')->pluck('id')->contains($key)) selected  @endif value="{{$key}}">{{ucfirst($parameter)}}</option>
                    @endforeach
                </select>
                @error('parameters')
                    <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <div class="col-12 py-2">
                <label for="companyFilter">Companies</label><br/>
                <select name="companies[]" id="companyFilter" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                    @foreach($companies as $company)
                        <option @if(optional(optional($data)->companies())->exists() && $data->getRelationValue('companies')->pluck('id')->contains($company->getAttribute('id'))) selected  @endif value="{{$company->getAttribute('id')}}">{{ucfirst($company->getAttribute('name'))}}</option>
                    @endforeach
                </select>
                @error('companies')
                    <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
        </div>
        @if($action)
            <x-input::submit />
        @endif
    </form>
@endsection

@section('scripts')
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

@if(is_null($action))
<script>
    $('input').attr('readonly', true)
    $('select').attr('disabled', true)
    $('textarea').attr('readonly', true)
</script>
@endif

<script>
    $('.filterSelector').selectpicker()
</script>

@endsection