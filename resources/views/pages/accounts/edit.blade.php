@extends('layouts.main')

@section('title', trans('translates.navbar.account'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('banks.index')">
            @lang('translates.navbar.account')
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
        <div class=" row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text name="name" :value="$data->getAttribute('name')" :label="trans('translates.columns.name')" width="6" class="pr-3" />
                    <x-input::select name="company_id" :value="$data->getAttribute('company_id')" :label="trans('translates.columns.company')"  width="6" class="pr-3" :options="$companies"/>
                    <x-input::text name="customCompany" :value="$data->getAttribute('account')" :label="trans('translates.columns.company')" width="6" class="pr-3 customCompany" />
                    <x-input::text name="amount" :label="trans('translates.columns.amount')" :value="$data->getAttribute('amount')" width="6" class="pr-2 amount" />
                    <div class="form-group col-6">
                        <label>@lang('translates.general.currency')</label>
                        <select name="currency" class="form-control">
                            <option value="AZN" @if($data->getAttribute('currency') == 'AZN') selected @endif>AZN</option>
                            <option value="USD" @if($data->getAttribute('currency') == 'USD') selected @endif>USD</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')

    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
    <script>

        $('select[name="company_id"]').change(function() {
            var selectedCompanyId = $(this).val();

            if (selectedCompanyId > 0) {
                $('.customCompany').hide();
            } else {
                $('.customCompany').show();
            }
        });
    </script>

@endsection
