@extends('layouts.main')

@section('title', trans('translates.navbar.certificate'))

@section('content')
    <x-bread-crumb xmlns:x-input="http://www.w3.org/1999/html">
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('certificates.index')">
            @lang('translates.navbar.certificate')
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
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="optional($data)->getTranslation('name', $key)"     label="Details"     width="6" class="pr-3" />
                                    <x-input::textarea     name="translate[detail][{{$key}}]"  :value="optional($data)->getTranslation('detail', $key)"  label="certificate class attribute"  width="6" class="pr-3" />
                                </div>
                            </div>
                        @endforeach
                    </x-translate>

{{--                    <x-input::select  name="organization_id" :value="optional($data)->getAttribute('organization_id')" label="Position role"  width="6" class="pr-3" :options="$organizations"/>--}}

                </div>

            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
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
