@extends('layouts.main')

@section('title', __('translates.navbar.asan_imza'))

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('asan-imza.index')">
            @lang('translates.navbar.asan_imza')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{$data->getRelationValue('user')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class="tab-content row mt-4">

            <div class="form-group col-6">
                <label for="company_id">@lang('translates.fields.company')</label><br/>
                <select class="form-control" name="company_id" id="company_id" data-width="fit">
                    @foreach($companies as $company)
                        <option @if($data->getAttribute('company_id') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6">
                <label for="user_id">@lang('translates.columns.user')</label><br/>
                <select class="select2 form-control" name="user_id" id="user_id">
                    @foreach($users as $user)
                        <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>

            <x-input::text  name="phone"    :label="__('translates.fields.phone')"   :value="$data->getAttribute('phone')"    width="6" required=""/>
            <x-input::text  name="asan_id"   label="Asan ID"    :value="$data->getAttribute('asan_id')"    width="6" required=""/>

        </div>
        @if($action)
                <x-input::submit :value="__('translates.buttons.save')"/>
         @endif
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if(is_null($action))
        <script>
            $('select').attr('disabled', true)
        </script>
    @endif
    <script>
        const select2 = $('.select2');

        select2.select2({
            theme: 'bootstrap4',
        });

        select2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection
