@extends('layouts.main')

@section('title', __('translates.navbar.position'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('positions.index')">
            @lang('translates.navbar.position')
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
        @bind($data)

        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class=" row mt-4" >
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

                    <x-form-group  class="pr-3 col-12 col-lg-3">
                        <x-form-select name="role_id" label="Position role" :options="$roles" />
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-3">
                        <x-form-select name="department_id" label="Position department" :options="$departments" />
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-3"  >
                        <x-form-input type="number" name="order" label="Position Order"/>
                    </x-form-group>
                </div>
                @if(auth()->user()->isDeveloper())
                    <x-permissions :model="$data" :action="$action" />
                @endif
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
        @endbind
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
