@extends('layouts.main')

@section('title', __('translates.navbar.organization'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('organizations.index')">
            @lang('translates.navbar.organization')
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
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="optional($data)->getTranslation('name', $key)"     label="Name"     width="6" class="pr-3" />
                                    <x-input::textarea     name="translate[detail][{{$key}}]"  :value="optional($data)->getTranslation('detail', $key)"  label="Detail"  width="6" class="pr-3" />
                                </div>
                            </div>
                        @endforeach
                    </x-translate>

                    <div class="col-12 col-md-6 pr-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @if(optional($data)->getAttribute('is_certificate') === true) checked @endif name="is_certificate" id="data-certificate">
                            <label class="form-check-label" for="data-certificate">
                                Is Certificate
                            </label>
                        </div>
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
    <script>
        $( "input[name='datetime']" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showAnim: "slideDown",
        });

    </script>
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
