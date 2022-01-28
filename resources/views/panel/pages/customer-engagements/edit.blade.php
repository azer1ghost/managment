@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@extends('layouts.main')
@section('title', trans('translates.navbar.customer_engagement'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('customer-engagement.index')">
            @lang('translates.navbar.customer_engagement')
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
                <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                <select name="client_id" id="data-client-type" style="width: 100% !important;">
                    @if(is_numeric($data->getAttribute('client_id')))
                        <option value="{{$data->getAttribute('client_id')}}">{{$data->getRelationValue('client')->getAttribute('fullname_with_voen')}}</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-6">
                <label for="user_id">@lang('translates.columns.user')</label><br/>
                <select class="select2 form-control" name="user_id" id="user_id">
                    <option value="">@lang('translates.general.user_select')</option>
                    @foreach($users as $user)
                        <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6">
                <label for="partner_id">@lang('translates.columns.partner')</label><br/>
                <select class="select2 form-control" name="partner_id" id="partner_id">
                    <option value="">@lang('translates.general.partner_select')</option>
                    @foreach($partners as $partner)
                        <option @if($data->getAttribute('partner_id') == $partner->id) selected @endif value="{{$partner->id}}">{{$partner->getAttribute('name')}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
    <script>
        const clientSelect2 = $('select[name="client_id"]');
        clientSelect2.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('clients.search')}}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })
        clientSelect2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        const userSelect2 = $('select[name="user_id"]');

        userSelect2.select2({
            theme: 'bootstrap4',
        });

        userSelect2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        const partnerSelect2 = $('select[name="partner_id"]');

        partnerSelect2.select2({
            theme: 'bootstrap4',
        });

        partnerSelect2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection
