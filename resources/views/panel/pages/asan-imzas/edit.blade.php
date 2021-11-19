@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('asan-imzas.index')">
            @lang('translates.navbar.asan_imza')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getRelationValue('user')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content row mt-4" >
            <div class="form-group col-6 py-2">
                <label for="company_id">Company</label><br/>
                <select class="form-control"  name="company_id" id="company_id" data-width="fit"   >
                    @foreach($companies as $company)
                        <option @if(optional($data)->getAttribute('company_id') == $company->id) selected  @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>

            </div>
            <div  class="form-group col-6 py-2">
                <label for="user_id">Users</label><br/>
                <select class="form-control" name="user_id" id="user_id" >
                    @foreach($users as $user)
                        <option @if(optional($data)->getAttribute('user_id') == $user->id) selected  @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>

        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>

@endsection

@if(is_null($action))
@section('scripts')
    <script>
        $('input').attr('readonly', true)
        $('select').attr('disabled', true)
        $('input[type="file"]').attr('disabled', true)
        $('textarea').attr('readonly', true)
    </script>
@endsection
@endif
