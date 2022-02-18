@extends('layouts.main')

@section('title', __('translates.navbar.role'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('roles.index')">
            @lang('translates.navbar.role')
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
        <input type="hidden" name="id" value="{{optional($data)->id}}">
        <div class=" row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-form-group class="pr-3 col-12 col-lg-6">
                                        <x-form-input name="name" :language="$key" label="Role name {{$key}}" placeholder="Rol daxil edin"/>
                                    </x-form-group>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                    <x-form-group class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="key" label="Role key" placeholder="Meeting name daxil edin"/>
                    </x-form-group>
                </div>
                @if(auth()->user()->isDeveloper())
                    <x-permissions :model="$data" :action="$action" />
                @endif
            </div>
        </div>
        @endbind
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
