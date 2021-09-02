@extends('layouts.main')

@section('title', __('translates.navbar.parameter'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
<div class="container" xmlns:x-input="http://www.w3.org/1999/html">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('parameters.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                    Parameters
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
                        <div class="tab-content form-row mt-4" >
                            <div class="form-group col-12 col-md-4 pr-3">
                                <label for="data-option_id">Parent Option id</label>
                                <select class="form-control @error('option_id') is-invalid @enderror" name="option_id" id="data-option_id" style="padding: .375rem 0.75rem !important;">
                                    <option value="">Parent Option id {{__('translates.placeholders.choose')}}</option>
                                    @foreach($options as $option)
                                        <option @if($option->getAttribute('id') == optional($data)->getAttribute('option_id')) selected @endif value="{{$option->getAttribute('id')}}">{{$option->getAttribute('text')}}</option>
                                    @endforeach
                                </select>
                                @error('option_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <x-input::select   name="type"          :value="optional($data)->getAttribute('type')"          width="4" class="pr-3" :options="$types" />
                            <x-input::text     name="name"          :value="optional($data)->getAttribute('name')"          width="4" class="pr-3" />
                            <x-input::text     name="label"         :value="optional($data)->getAttribute('label')"         width="4" class="pr-3" />
                            <x-input::text     name="placeholder"   :value="optional($data)->getAttribute('placeholder')"   width="4" class="pr-3" />
                            <x-input::number   name="order"         :value="optional($data)->getAttribute('order')"         width="4" class="pr-3" label="Order"/>
                            <div class="col-12">
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
                            @if (optional($data)->getAttribute('type') == 'select')
                                <div class="col-12 py-2" id="parameter-options">
                                    <p class="mb-2">Options</p>
                                    @forelse ($parameterCompanies as $company)
                                        <label for="optionFilter-{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</label>
                                        <select name="options[{{$company->getAttribute('id')}}][]" id="optionFilter-{{$company->getAttribute('id')}}" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                                            @foreach ($options as $option)
                                                <option @if($company->options(optional($data)->getAttribute('id'))->pluck('id')->contains($option->getAttribute('id'))) selected  @endif value="{{$option->getAttribute('id')}}">{{ucfirst($option->getAttribute('text'))}}</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                    @empty
                                        <span>No companies yet</span>
                                    @endforelse
                                    @error('options')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        @if($action)
                            <x-input::submit />
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
    $('#data-type').change(function(){
        if (this.value === 'text') {
            $('#parameter-options').hide()
            $('#parameter-options select').attr('disabled', true)
        }else if (this.value === 'select'){
            $('#parameter-options').show()
            $('#parameter-options select').attr('disabled', false)
        }
    });
</script>

@endsection