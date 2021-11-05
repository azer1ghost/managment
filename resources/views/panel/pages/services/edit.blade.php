@extends('layouts.main')

@section('title', __('translates.navbar.service'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('services.index')">
            @lang('translates.navbar.service')
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

        <div class="tab-content row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="optional($data)->getTranslation('name', $key)"     label="Position name {{$key}}"     width="6" class="pr-3" />
                                </div>
                            </div>
                        @endforeach
                    </x-translate>

                    <x-input::textarea name="detail" :value="optional($data)->getAttribute('detail')" label="Service detail" width="6" class="pr-3"/>

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
                    <div class="form-group col-12 col-md-6">
                        <label for="data-department_id">Department Select</label>
                        <select name="department_id" id="data-department_id" class="form-control">
                            <option value="" selected>Department Select</option>
                            @foreach($departments as $department)
                                <option @if(optional($data)->getAttribute('department_id') === $department->getAttribute('id') ) selected
                                        @endif value="{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
