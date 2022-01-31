@extends('layouts.main')

@section('title', __('translates.navbar.sales_activities_type'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('sales-activities.index')">
            @lang('translates.navbar.sales_activities')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf

        <div class="tab-content row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label for="sales_activity_type_id">@lang('translates.navbar.sales_activities_type')</label>
                        <select name="sales_activity_type_id" id="sales_activity_type_id" class="form-control" style="width: 100% !important;">
                            <option selected value="{{$data->getAttribute('sales_activity_type_id')}}">
                                {{$data->getRelationValue('salesActivityType')->getAttribute('name')}}
                            </option>
                        </select>
                    </div>

                    @if(str_contains($data->getRelationValue('salesActivityType')->getAttribute('hard_columns'), '4'))
                        <x-input::text  name="name"  :value="$data->getAttribute('name')"  :label="trans('translates.fields.name')"  width="6" class="pr-3" />
                    @endif

                    @if(str_contains($data->getRelationValue('salesActivityType')->getAttribute('hard_columns'), '5'))
                        <x-input::text  name="address"  :value="$data->getAttribute('address')"  :label="trans('translates.fields.address')"  width="6" class="pr-3" />
                    @endif

                    @if(str_contains($data->getRelationValue('salesActivityType')->getAttribute('hard_columns'), '3'))
                        <x-input::text  name="activity_area"  :value="$data->getAttribute('activity_area')"  :label="trans('translates.columns.activity_area')"  width="6" class="pr-3" />
                    @endif

                    @if(auth()->user()->getAttribute('department_id') == \App\Models\Department::SALES || auth()->user()->isDeveloper())
                        <div class="form-group col-12 col-md-6">
                            <label for="clientFilter">@lang('translates.fields.client')</label>
                            <select name="client_id" id="clientFilter" class="form-control" style="width: 100% !important;">
                                <option selected value="{{$data->getAttribute('client_id')}}">
                                    {{$data->getRelationValue('client')->getAttribute('name_with_voen')}}
                                </option>
                            </select>
                        </div>
                    @endif

                    @if(str_contains($data->getRelationValue('salesActivityType')->getAttribute('hard_columns'), '1'))
                        <x-input::select  name="organization_id" :value="$data->getAttribute('organization_id')" :label="trans('translates.columns.organization')"  width="6" class="pr-3" :options="$organizations"/>
                    @endif

                    @if(str_contains($data->getRelationValue('salesActivityType')->getAttribute('hard_columns'), '2'))
                        <x-input::select  name="certificate_id" :value="$data->getAttribute('certificate_id')"    :label="trans('translates.columns.is_certificate')"  width="6" class="pr-3" :options="$certificates"/>
                    @endif

                    <x-input::text name="datetime" readonly :label="__('translates.fields.date')" value="{{optional($data->getAttribute('datetime'))->format('Y-m-d H:i')}}" width="6" class="pr-3 custom-single-daterange" />
                    <x-input::textarea  name="result"  :value="$data->getAttribute('result')"  label="Result"  width="6" class="pr-3" />
                </div>

                <div class="my-3">
                    <h5>@lang('translates.sales_supply.sales_supply')</h5>
                    @livewire('show-sales-supplies', ['salesActivity' => $data, 'action' => $action])
                </div>
            </div>
        </div>
        @if($method)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($method))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
