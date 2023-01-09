@extends('layouts.main')

@section('title', trans('translates.navbar.instruction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.instruction')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <style>
        h2 {
            background: #07625c;
            border: 1px solid;
            text-align: center;
            padding: 5px;
            color: whitesmoke;
            cursor: pointer;
        }
    </style>
    <div class="row mb-2">
        <div class="col-12 justify-content-center">
            <h2 data-toggle="collapse" href="#task">@lang('translates.navbar.task')</h2>
            <div class="col-12 collapse" id="task">
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/task.mp4')}}" type="video/mp4"></video>
            </div>
            @if(auth()->user()->isDirector() || auth()->user()->hasPermission('update-user'))
                <h2 data-toggle="collapse" href="#hr">@lang('translates.navbar.human_resources')</h2>
                <div class="collapse" id="hr">
                    <p class="text-center">Mobil Management</p>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/hr.mp4')}}" type="video/mp4"></video>
                    <p class="text-center">Xitam</p>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/xitam.mp4')}}" type="video/mp4"></video>
                    <p class="text-center">Əmək haqqı</p>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/salary.mp4')}}" type="video/mp4"></video>
                </div>
             @endif
            <h2 data-toggle="collapse" href="#inquiry">@lang('translates.navbar.inquiry')</h2>
            <div class="collapse" id="inquiry">
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/inquiry.mp4')}}" type="video/mp4"></video>
            </div>

            <h2 data-toggle="collapse" href="#calendar">@lang('translates.navbar.calendar')</h2>
            <div class="collapse" id="calendar">
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/teqvim.mp4')}}" type="video/mp4"></video>
            </div>

            <h2 data-toggle="collapse" href="#work">@lang('translates.navbar.work')</h2>
            <div class="collapse" id="work">
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/isler.mp4')}}" type="video/mp4"></video>
            </div>

            @if(auth()->user()->isDirector() || auth()->user()->hasPermission('editTable-work'))
                <h2 data-toggle="collapse" href="#declaration">@lang('translates.general.declaration')</h2>
                <div class="collapse" id="declaration">
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/declaration.mp4')}}" type="video/mp4"></video>
                </div>
            @endif
        </div>
    </div>

@endsection