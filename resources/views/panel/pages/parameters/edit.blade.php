@extends('layouts.main')

@section('title', __('translates.navbar.parameter'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('parameters.index')">
            @lang('translates.navbar.parameter')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('label')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content form-row mt-4" >
            <x-translate>
                @foreach(config('app.locales') as $key => $locale)
                    <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                        <div class="row">
                            <x-input::text  name="translate[label][{{$key}}]"  :value="optional($data)->getTranslation('label', $key)"     label="Parameter label"     width="4" class="pr-3" />
                            <x-input::text  name="translate[placeholder][{{$key}}]"  :value="optional($data)->getTranslation('placeholder', $key)"     label="Parameter placeholder"     width="4" class="pr-3" />
                        </div>
                    </div>
                @endforeach
            </x-translate>
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
            <x-input::text     name="attributes"    :value="optional($data)->getAttribute('attributes')"    width="4" class="pr-3" />
            <x-input::number   name="order"         :value="optional($data)->getAttribute('order')"         width="4" class="pr-3" label="Order"/>

            <div class="col-12 mb-3">
                <label for="departmentFilter">Departments</label><br/>
                <select name="departments[]" id="departmentFilter" data-selected-text-format="count" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                    @foreach($departments as $department)
                        <option @if(optional(optional($data)->departments())->exists() && $data->getRelationValue('departments')->pluck('id')->contains($department->getAttribute('id'))) selected  @endif
                            value="{{$department->getAttribute('id')}}">
                                {{ucfirst($department->getAttribute('name'))}}
                        </option>
                    @endforeach
                </select>
                @error('departments')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="col-12 py-2" id="parameter-departments">
                <p class="mb-1" style="font-size: 16px"><strong>Department Companies</strong></p>
                @forelse (optional($data)->departments as $department)
                    <label for="companyFilter-{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</label>
                    <select name="companies[{{$department->getAttribute('id')}}][]" data-selected-text-format="count" id="companyFilter-{{$department->getAttribute('id')}}" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                        @foreach ($companies as $company)
                            <option @if($department->departmentCompanies()->where('company_parameter.parameter_id', optional($data)->getAttribute('id'))->get()->contains($company->getAttribute('id'))) selected  @endif
                            value="{{$company->getAttribute('id')}}">
                                {{$company->getAttribute('name')}}
                            </option>
                        @endforeach
                    </select>
                    <br/>
                @empty
                    <p class="mb-1">No departments yet</p>
                @endforelse
                @error('companies')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            @if (optional($data)->getAttribute('type') == 'select')
                <div class="col-12 py-2" id="parameter-options">
                    <p class="mb-1" style="font-size: 16px"><strong>Department Companies Options</strong></p>
                    @forelse (optional($data)->departments as $department)
                        <label><strong>{{$loop->iteration}}. {{$department->getAttribute('name')}}</strong></label>
                        <br/>
                        @forelse ($department->departmentCompanies()->where('company_parameter.parameter_id', optional($data)->getAttribute('id'))->get() as $departmentCompany)
                            <label for="optionFilter-{{$department->getAttribute('id')}}-{{$departmentCompany->getAttribute('id')}}">{{$departmentCompany->getAttribute('name')}}</label>
                            <select name="options[{{$department->getAttribute('id')}}][{{$departmentCompany->getAttribute('id')}}][]" data-selected-text-format="count" id="optionFilter-{{$department->getAttribute('id')}}-{{$departmentCompany->getAttribute('id')}}" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                                @foreach ($options as $option)
                                    <option @if($department->options()->where('option_parameter.company_id', $departmentCompany->getAttribute('id'))->where('option_parameter.parameter_id', optional($data)->getAttribute('id'))->get()->contains($option->getAttribute('id'))) selected  @endif
                                        value="{{$option->getAttribute('id')}}">
                                            {{$option->getAttribute('text')}}
                                    </option>
                                @endforeach
                            </select>
                            <br/>
                        @empty
                            <p class="mb-1">No departments companies yet</p>
                        @endforelse
                        <br/>
                    @empty
                        <p class="mb-1">No departments companies yet</p>
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